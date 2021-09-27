<?php

namespace App\Models;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PostCategory extends Model
{
    use HasFactory;

    private int $parent_id = 0;

    public static $rules = [
        'name' => 'required',
        'description' => '',
        'parent_id' => 'integer'
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
                TreePath::create([
                    'descendant_id' => $category->id,
                    'ancestor_id' => $category->id,
                    'entity_type' => self::class,
                ]);
                $category->createTreePath($category->parent_id);
            } catch (Exception $e) {
                DB::rollback();
            }
            DB::commit();
        });

        static::updated(function ($category) {
            DB::beginTransaction();
            try {
                $category->updateTreePath($category->parent_id);
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
     * Set the post category's parent id.
     *
     * @param  int  $value
     * @return void
     */
    public function setParentIdAttribute(int $value)
    {
        $this->parent_id = $value;
    }

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
    private function createTreePath(int $parent_id)
    {
        $treePaths = TreePath::where('descendant_id', $parent_id)
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
    private function updateTreePath(int $parent_id)
    {
        TreePath::where('ancestor_id', $this->id)
            ->where('descendant_id', '<>', $this->id)
            ->where('entity_type', self::class)
            ->delete();

        $this->createTreePath($parent_id);
    }
}
