<?php

namespace App\Models;

use Exception;
use App\Traits\Sluggable;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PostCategory extends Model
{
    use HasFactory, Sluggable;

    protected $generateSlugFrom = 'name';

    public static $rules = [
        'name' => 'required',
        'description' => '',
        'parent_id' => 'nullable|exists:post_categories,id'
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::created(function ($category) {
            DB::beginTransaction();
            try {
                $category->createTreePath();
            } catch (Exception $e) {
                DB::rollback();
            }
            DB::commit();
        });

        static::updated(function ($category) {
            DB::beginTransaction();
            try {
                $category->updateTreePath();
            } catch (Exception $e) {
                DB::rollback();
            }
            DB::commit();
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'parent_id',
    ];

    /**
     * The posts that belong to the category.
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

    /**
     * TODO
     */
    public function ancestors()
    {
        return $this
            ->belongsToMany(self::class, 'tree_paths', 'descendant_id', 'ancestor_id')
            ->wherePivot('entity_type', self::class);
    }

    /**
     * TODO
     */
    public function descendants()
    {
        return $this
            ->belongsToMany(self::class, 'tree_paths', 'ancestor_id', 'descendant_id')
            ->wherePivot('entity_type', self::class)
            ->whereRaw('
                tree_paths.ancestor_id <> tree_paths.descendant_id AND
                tree_paths.ancestor_id = (
                    SELECT MAX(tp.ancestor_id)
                    FROM tree_paths AS tp
                    WHERE tp.descendant_id = tree_paths.descendant_id AND 
                        tp.descendant_id <> tp.ancestor_id 
                    LIMIT 1
                )
            ')
            ->with('descendants');
    }

    /**
     * TODO
     */
    private function createTreePath()
    {
        TreePath::create([
            'descendant_id' => $this->id,
            'ancestor_id' => $this->id,
            'entity_type' => self::class,
        ]);
        $treePaths = TreePath::where('descendant_id', $this->parent_id)
            ->where('descendant_id', '<>', $this->id)
            ->where('entity_type', self::class)
            ->get();
        foreach ($treePaths as $treePath) {
            TreePath::create([
                'descendant_id' => $this->id,
                'ancestor_id' => $treePath->ancestor_id,
                'entity_type' => self::class,
            ]);
        }
    }

    /**
     * TODO
     */
    private function updateTreePath()
    {
        TreePath::where('descendant_id', $this->id)
            ->where('entity_type', self::class)
            ->delete();

        $this->createTreePath();
    }
}
