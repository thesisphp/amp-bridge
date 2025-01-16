<?php

declare(strict_types=1);

namespace Thesis\Ampridge;

use Amp\Socket\Socket;
use Thesis\ByteReader\Reader;
use Thesis\ByteReader\UnexpectedEof;
use Thesis\ByteWriter\WriteFailed;
use Thesis\ByteWriter\Writer;

/**
 * @api
 */
final class ReaderWriter implements
    Reader,
    Writer
{
    public function __construct(
        private readonly Socket $socket,
    ) {}

    public function read(int $limit): string
    {
        try {
            $bytes = $this->socket->read(limit: $limit) ?: null;
        } catch (\Throwable $e) {
            throw new UnexpectedEof($e->getMessage(), (int) $e->getCode(), $e);
        }

        if ($bytes === null) {
            throw new UnexpectedEof();
        }

        return $bytes;
    }

    public function write(string $bytes): void
    {
        try {
            $this->socket->write($bytes);
        } catch (\Throwable $e) {
            throw new WriteFailed($e->getMessage(), (int) $e->getCode(), $e);
        }
    }
}
