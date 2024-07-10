<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings'; // Tên bảng trong cơ sở dữ liệu

    protected $fillable = ['key', 'value']; // Các cột có thể gán giá trị
}
