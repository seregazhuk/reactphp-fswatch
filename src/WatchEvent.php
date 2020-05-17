<?php

declare(strict_types=1);

namespace Seregazhuk\FsWatch;

final class WatchEvent
{
    private int $bitwise;
    private string $file;

    private const NO_OP = 0;
    private const PLATFORM_SPECIFIC = 1;
    private const CREATED = 2;
    private const UPDATED = 4;
    private const REMOVED = 8;
    private const RENAMED = 16;
    private const OWNER_MODIFIED = 32;
    private const ATTRIBUTE_MODIFIED = 64;
    private const MOVED_FROM = 128;
    private const MOVED_TO = 256;
    private const IS_FILE = 512;
    private const IS_DIR = 1024;
    private const IS_SYM_LINK = 2048;
    private const LINK = 4096;
    private const OVERFLOW = 8192;

    public function __construct(string $file, int $bitwise)
    {
        $this->bitwise = $bitwise;
        $this->file = $file;
    }

    public function getBitwise(): int
    {
        return $this->bitwise;
    }

    public function getFile(): string
    {
        return $this->file;
    }

    public function isFile(): bool
    {
        return (bool)($this->bitwise & self::IS_FILE);
    }

    public function isDir(): bool
    {
        return (bool)($this->bitwise & self::IS_DIR);
    }

    public function isSymbolicLink(): bool
    {
        return (bool)($this->bitwise & self::IS_SYM_LINK);
    }

    public function isLink(): bool
    {
        return (bool)($this->bitwise & self::LINK);
    }

    public function noOp(): bool
    {
        return (bool)($this->bitwise & self::NO_OP);
    }

    public function attributeModified(): bool
    {
        return (bool)($this->bitwise & self::ATTRIBUTE_MODIFIED);
    }

    public function ownerModified(): bool
    {
        return (bool)($this->bitwise & self::OWNER_MODIFIED);
    }

    public function created(): bool
    {
        return (bool)($this->bitwise & self::CREATED);
    }

    public function removed(): bool
    {
        return (bool)($this->bitwise & self::REMOVED);
    }

    public function renamed(): bool
    {
        return (bool)($this->bitwise & self::RENAMED);
    }

    public function updated(): bool
    {
        return (bool)($this->bitwise & self::UPDATED);
    }
}
