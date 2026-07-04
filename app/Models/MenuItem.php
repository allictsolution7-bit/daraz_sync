<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $menu_id
 * @property int|null $parent_id
 * @property string $title
 * @property string|null $url
 * @property string $target
 * @property string|null $icon_class
 * @property int $order
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, MenuItem> $children
 * @property-read int|null $children_count
 * @property-read \App\Models\Menu $menu
 * @property-read MenuItem|null $parent
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereIconClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereMenuId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereTarget($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereUrl($value)
 * @mixin \Eloquent
 */
class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_id',
        'parent_id',
        'title',
        'url',
        'target',
        'icon_class',
        'order',
        'status',
        'has_mega_menu',
        'mega_menu_config',
        'mega_menu_headers',
    ];

    protected $casts = [
        'has_mega_menu' => 'boolean',
        'mega_menu_config' => 'array',
        'mega_menu_headers' => 'array',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function parent()
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->where('status', true)->orderBy('order');
    }

    public function allChildren()
    {
        return $this->children()->with('allChildren');
    }

    // Get children with all nested relationships loaded
    public function getChildrenWithNested()
    {
        return $this->children()->with('children.children.children')->get();
    }

    /**
     * Check if this menu item can have a mega menu (top-level only)
     */
    public function canHaveMegaMenu(): bool
    {
        return is_null($this->parent_id);
    }

    /**
     * Get the menus configured for mega menu columns
     * Returns array of Menu models keyed by column name
     */
    public function getMegaMenuMenus(): array
    {
        if (!$this->has_mega_menu || empty($this->mega_menu_config)) {
            return [];
        }

        $menus = [];
        $menuIds = array_filter($this->mega_menu_config);

        if (!empty($menuIds)) {
            $loadedMenus = Menu::whereIn('id', $menuIds)
                ->where('status', true)
                ->with(['menuItems' => function ($query) {
                    $query->whereNull('parent_id')
                        ->where('status', true)
                        ->orderBy('order');
                }])
                ->get()
                ->keyBy('id');

            foreach ($this->mega_menu_config as $column => $menuId) {
                if ($menuId && isset($loadedMenus[$menuId])) {
                    $menus[$column] = $loadedMenus[$menuId];
                }
            }
        }

        return $menus;
    }

    /**
     * Get header for a specific column
     */
    public function getMegaMenuHeader(string $column): ?string
    {
        return $this->mega_menu_headers[$column] ?? null;
    }
}