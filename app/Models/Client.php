<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Authenticatable
{
    use HasFactory;

    protected $table = 'clients';

    protected $guarded = [];

    protected $hidden = [
        'password',
    ];

    public function permissions()
    {
        return $this->belongsToMany(
            Permission::class,
            'permission_client',
            'client_id',
            'permission'
        );
    }

    private $clientPermissions = null;

    public function hasPermission(string $permissionName): bool
    {
        if ($this->clientPermissions === null) {
            $this->clientPermissions = $this->permissions()
                ->pluck('name')
                ->toArray();
        }

        return in_array($permissionName, $this->clientPermissions);
    }

    public function assignDefaultPermissions()
    {
        $permissionIds = \DB::table('permissions')
            ->whereIn('name', [
                'view-dashboard',
                'view-customer-quotations',
            ])
            ->pluck('id');

        foreach ($permissionIds as $permissionId) {
            \DB::table('permission_client')->insertOrIgnore([
                'client_id' => $this->id,
                'permission' => $permissionId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function salesman()
    {
        return $this->belongsTo(Employee::class, 'salesman_id');
    }
    

}
