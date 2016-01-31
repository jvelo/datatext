<?php

namespace Jvelo\Paidia\Models;

use Illuminate\Database\Eloquent\Model;
use Jvelo\Eloquent\UuidKey;
use Jvelo\Paidia\Support\UserProvider;
use Eloquent\Dialect\Json;
use Cocur\Slugify\Slugify;
use Carbon\Carbon;
use DiffMatchPatch\DiffMatchPatch;
use Illuminate\Database\Capsule\Manager as DB;

class Page extends Model {

    use UuidKey;
    use Json;

    protected $table = 'page';

    protected $jsonColumns = ['metadata'];

    public $timestamps = false;

    protected $casts = [
        'id' => 'string'
    ];

    public function __construct()
    {
        parent::__construct();
        $this->metadata = '{}';
    }

    /**
     * Ensure the save is being done in a transaction so that pages are always consistent with their revisions (saved
     * in the creating/updating event callbacks, see below.
     *
     * @inheritDoc
     *
     * @param array $options
     * @return mixed
     */
    public function save(array $options = [])
    {
        return DB::transaction(function () use ($options) {
            return parent::save($options);
        });
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function($page)
        {
            $slugify = new Slugify();
            $page->slug = $slugify->slugify($page->title);
            $page->setJsonAttribute('metadata', 'author', UserProvider::getCurrentUserId());
        });

        static::created(function($page)
        {
            $revision = new Revision;
            $revision->page_id = $page->id;
            $revision->revision_id = 0;

            $dmp = new DiffMatchPatch();

            // Content patch
            $contentPatches = $dmp->patch_make("", $page->content);
            $revision->content_patch = $dmp->patch_toText($contentPatches);

            // Title patch
            $titlePatches = $dmp->patch_make("", $page->title);
            $revision->title_patch = $dmp->patch_toText($titlePatches);

            $revision->author = UserProvider::getCurrentUserId();
            $revision->created_at = Carbon::now();

            $revision->save();
        });

        static::updating(function($updatingPage){
            // Get the page again from DB so we are sure we have up-to-date content to make the diff from
            $page = Page::find($updatingPage->id);

            $lastRevision = Revision::where('page_id', $page->id)->orderBy('revision_id','DESC')->firstOrFail();

            $revision = new Revision;
            $revision->page_id = $page->id;
            $revision->revision_id = $lastRevision->revision_id + 1;

            $dmp = new DiffMatchPatch();

            // Content patch
            $contentPatches = $dmp->patch_make($page->getOriginal('content'), $updatingPage->content);
            $revision->content_patch = $dmp->patch_toText($contentPatches);

            // Title patch
            $titlePatches = $dmp->patch_make($page->getOriginal('title'), $updatingPage->title);
            $revision->title_patch = $dmp->patch_toText($titlePatches);

            $revision->author = UserProvider::getCurrentUserId();
            $revision->created_at = Carbon::now();

            $revision->save();
        });
    }
}