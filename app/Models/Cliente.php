<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    protected $table = 'clientes';

    protected $fillable = [
        // Empresa
        'nombre_empresa',
        'tipo_empresa',
        'industria',
        'descripcion',
        
        // Fechas
        'fecha_registro',
        'fecha_creacion',
        'fecha_cotizacion',
        'fecha_factura',
        'estado_cuenta',
        
        // Contacto
        'correo',
        'telefono',
        'telefono_alternativo',
        'whatsapp',
        
        // Ubicación
        'pais',
        'estado',
        'ciudad',
        'direccion',
        'codigo_postal',
        
        // Sitio Web
        'nombre_sitio',
        'url_sitio',
        'hosting',
        'dominio_expira',
        
        // Redes Sociales
        'redes_sociales',
        
        // Notas
        'notas',
    ];

    protected $casts = [
        'fecha_registro' => 'date',
        'fecha_creacion' => 'date',
        'fecha_cotizacion' => 'date',
        'fecha_factura' => 'date',
        'dominio_expira' => 'date',
        'redes_sociales' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function facturas(): HasMany
    {
        return $this->hasMany(Factura::class)->orderBy('created_at', 'desc');
    }

    public function citas(): HasMany
    {
        return $this->hasMany(Cita::class);
    }

    public function cotizaciones(): HasMany
    {
        return $this->hasMany(Cotizacion::class);
    }

    public function contratos(): HasMany
    {
        return $this->hasMany(Contrato::class)->orderBy('created_at', 'desc');
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class)->orderBy('created_at', 'desc');
    }

    public function sitios(): HasMany
    {
        return $this->hasMany(Sitio::class);
    }
}
