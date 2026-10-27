<?php

namespace App\Services\Ai;

use RuntimeException;

/**
 * Kesalahan saat memanggil AI. Pesannya aman ditampilkan ke pengguna.
 */
class AiException extends RuntimeException {}
