<?php declare(strict_types=1);

namespace App\Models\Data;

use App\Models\Validation\ValidIf;
use App\Models\Products;
use Illuminate\Support\Carbon;

class ProductsData extends DataObject
{
    #[ValidIf(['gt:0'])]
    public int $id {
        set => $this->validateProperty('id', $value);
    }

    #[ValidIf(['min:1', 'max:255'])]
    public string $name {
        set => $this->validateProperty('name', $value);
    }

    #[ValidIf(['min:1'])]
    public string $price {
        set => $this->validateProperty('price', $value);
    }

    #[ValidIf(['in:visible,hidden'])]
    public string $status {
        set => $this->validateProperty('status', $value);
    }

    // no validation needed
    public int $quantity {
        set => $this->validateProperty('quantity', $value);
    }

    #[ValidIf(['nullable', 'date'])]
    public ?Carbon $created_at {
        set => $this->validateProperty('created_at', $value);
    }

    #[ValidIf(['nullable', 'date'])]
    public ?Carbon $updated_at {
        set => $this->validateProperty('updated_at', $value);
    }

    /**
     * @return class-string
     */
    protected function getEloquentClassName(): string
    {
        return Products::class;
    }
}
