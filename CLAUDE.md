# CLAUDE.md - Module Item

## Module Overview

`hanafalah/module-item` is a Laravel package for managing items and inventory in healthcare facilities (klinik/puskesmas). It provides comprehensive item management including stock tracking, inventory assets, compositions, variants, and card stock movements.

**Namespace:** `Hanafalah\ModuleItem`

**Config Name:** `module-item`

## Dependencies

```json
{
    "hanafalah/laravel-support": "dev-main",
    "hanafalah/module-service": "dev-main",
    "hanafalah/module-warehouse": "dev-main"
}
```

## Directory Structure

```
src/
├── Commands/              # Artisan commands (install)
├── Concerns/              # Traits for models (HasItem, HasInventory, HasItemStock, etc.)
├── Contracts/
│   ├── Data/              # Data contract interfaces
│   └── Schemas/           # Schema contract interfaces
├── Data/                  # Spatie Data Transfer Objects
├── Providers/             # Service providers
├── Resources/             # API Resources (View/Show for each entity)
├── Schemas/               # Business logic implementations
├── Supports/              # Base classes (BaseModuleItem)
├── ModuleItem.php         # Main package class
└── ModuleItemServiceProvider.php
assets/
├── config/config.php      # Module configuration
└── database/migrations/   # Database migrations
```

## Service Provider Warning

**CRITICAL:** The `ModuleItemServiceProvider` extends `Hanafalah\LaravelSupport\Providers\BaseServiceProvider`.

When working with this module:
- Do NOT override the `register()` method without calling parent functionality
- The `registers(['*'])` call auto-registers all schemas, contracts, and resources
- The `registerMainClass()` binds `ModuleItem` to the container
- Migrations are published from `assets/database/migrations`

```php
class ModuleItemServiceProvider extends BaseServiceProvider
{
    public function register(){
        $this->registerMainClass(ModuleItem::class)
            ->registerCommandService(Providers\CommandServiceProvider::class)
            ->registers(['*']);
    }
}
```

## Core Entities

### Item
Central entity representing sellable/trackable items with polymorphic references.

**Key Fields:**
- `reference_type`, `reference_id` - Polymorphic relation to item source (e.g., Material)
- `barcode`, `name`, `item_code`
- `unit_id` - Selling form/unit of measure
- `cogs` - Cost of goods sold
- `selling_price`, `margin`, `tax`
- `min_stock`, `is_using_batch`
- `net_unit_id`, `net_qty`, `netto`

**Trait:** `HasItem` - Add to models that should have an associated Item record.

### Inventory
Manages inventory records linked to office supplies, stuff supplies, or other inventory types.

**Key Fields:**
- `reference_type`, `reference_id` - Polymorphic relation
- `name`, `inventory_code`
- `brand_id`, `supply_category_id`

**Trait:** `HasInventory` - Auto-creates inventory records on model creation.

### ItemStock
Extends `ModuleWarehouse\Schemas\Stock` for item-specific stock management.

**Trait:** `HasItemStock` - Provides `stock()` and `stocks()` relationships.

### CardStock
Tracks stock movements and transactions for items. Integrates with procurement and tax calculations.

**Key Fields:**
- `item_id`, `transaction_id`
- `reference_type`, `reference_id`
- `receive_qty`, `request_qty`, `total_qty`
- `total_cogs`, `total_tax`

### InventoryAsset
Tracks physical inventory assets with usage assignment.

**Key Fields:**
- `name`, `used_by_type`, `used_by_id`

### Supporting Entities
- **Brand** - Item brands (extends ItemStuff)
- **SupplyCategory** - Categories for supplies
- **UnitOfMeasure** - Units of measurement
- **SellingForm** - Selling/packaging forms
- **NetUnit** - Net content units
- **Composition** - Item compositions/ingredients
- **CompositionUnit** - Units for compositions
- **Variant** - Product variants
- **ItemHasVariant** - Links items to variants
- **StuffSupply** - Supply items
- **OfficeSupply** - Office supply items

## Traits (Concerns)

### HasItem
Automatically creates/updates an `Item` record when the parent model is created/updated.

```php
use Hanafalah\ModuleItem\Concerns\HasItem;

class Medicine extends Model
{
    use HasItem;
    // Automatically creates item() relation
}
```

### HasInventory
Similar to HasItem but for `Inventory` records.

```php
use Hanafalah\ModuleItem\Concerns\HasInventory;

class OfficeSupply extends Model
{
    use HasInventory;
    // Automatically creates inventory() relation
}
```

### HasItemStock
Provides stock relationships without auto-creation.

```php
use Hanafalah\ModuleItem\Concerns\HasItemStock;

class Product extends Model
{
    use HasItemStock;
    // Provides stock() and stocks() relations
}
```

### HasComposition
For models that have compositions/ingredients.

### HasMaterial
For models representing raw materials.

### HasItemWithInventoryAsset
Combines item and inventory asset functionality.

## Configuration

Located at `assets/config/config.php`:

```php
return [
    'namespace' => 'Hanafalah\ModuleItem',

    // Price update settings for procurement
    'update_price_from_procurement' => [
        'enable' => true,
        'method' => 'AVERAGE'  // Averaging method for COGS
    ],

    // Item reference type mappings
    'item_reference_types' => [
        'material' => ['schema' => 'Material']
    ],

    // Inventory type mappings
    'inventory_types' => [
        'office_supply' => ['schema' => 'OfficeSupply'],
        'stuff_supply' => ['schema' => 'StuffSupply']
    ]
];
```

## Schema Pattern

All schemas extend `BaseModuleItem` which extends `PackageManagement`:

```php
class Item extends BaseModuleItem implements ContractsItem
{
    protected string $__entity = 'Item';  // Entity name for model resolution

    protected array $__cache = [          // Cache configuration
        'index' => [
            'name'     => 'item',
            'tags'     => ['item', 'item-index'],
            'duration' => 60 * 12         // 12 hours
        ]
    ];

    public function prepareStoreItem(ItemData $item_dto): Model
    {
        // Business logic implementation
    }
}
```

## Data Transfer Objects

Uses Spatie Laravel Data for DTOs:

```php
use Hanafalah\ModuleItem\Data\ItemData;

$item_dto = ItemData::from([
    'name' => 'Paracetamol 500mg',
    'reference_type' => 'Material',
    'reference_id' => $material->id,
    'cogs' => 5000,
    'margin' => 20,
    'is_using_batch' => true
]);

$item = $this->schemaContract('item')->prepareStoreItem($item_dto);
```

## API Resources

Each entity has two resource types:
- **ViewXxx** - For list/index views (minimal data)
- **ShowXxx** - For detail views (full data with relations)

Example: `ViewItem`, `ShowItem`, `ViewInventory`, `ShowInventory`

## Installation

```bash
php artisan module-item:install
```

This publishes migrations from the package.

## Common Usage Patterns

### Creating an Item with Stock

```php
$item_dto = ItemData::from([
    'name' => 'Medicine A',
    'reference_type' => 'Material',
    'reference_id' => $material_id,
    'cogs' => 10000,
    'selling_price' => 15000,
    'item_stock' => [
        'warehouse_id' => $warehouse_id,
        'warehouse_type' => 'Warehouse',
        'stock' => 100
    ]
]);

$item = app('item_schema')->prepareStoreItem($item_dto);
```

### Using Schema Contracts

```php
// Get schema via contract
$itemSchema = $this->schemaContract('item');
$inventorySchema = $this->schemaContract('inventory');
$cardStockSchema = $this->schemaContract('card_stock');

// Store operations
$item = $itemSchema->prepareStoreItem($dto);
$inventory = $inventorySchema->prepareStoreInventory($dto);
```

## Integration with Module Warehouse

`ItemStock` extends `ModuleWarehouse\Schemas\Stock`, inheriting stock management functionality:
- Stock batch tracking
- Warehouse assignments
- Stock movement recording

## Cache Tags

When clearing cache, use these tags:
- `item`, `item-index`
- `inventory`, `inventory-index`
- `inventory_asset`, `inventory_asset-index`
- `brand`, `brand-index`

## Octane Considerations

This module follows Wellmed's Octane isolation requirements:
- No tenant-specific data stored in static properties
- Schema instances are request-scoped via container bindings
- Cache tags are tenant-aware when used with multi-tenant setup
