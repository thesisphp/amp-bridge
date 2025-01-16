<?php

declare(strict_types=1);

namespace Thesis\Ampridge;

use Amp\Cancellation;
use Amp\Socket\Socket;
use Thesis\ByteReader\Reader;
use Thesis\ByteReader\ReaderIsClosed;
use Thesis\ByteWriter\Writer;
use Thesis\ByteWriter\WriterIsClosed;

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

    public function read(int $limit, ?Cancellation $cancellation = null): string
    {
        try {
            $bytes = $this->socket->read(cancellation: $cancellation, limit: $limit) ?: null;
        } catch (\Throwable $e) {
            throw new ReaderIsClosed($e->getMessage(), (int) $e->getCode(), $e);
        }

        if ($bytes === null) {
            throw new ReaderIsClosed();
        }

        return $bytes;
    }

    public function write(string $bytes): void
    {
        try {
            $this->socket->write($bytes);
        } catch (\Throwable $e) {
            throw new WriterIsClosed($e->getMessage(), (int) $e->getCode(), $e);
        }
    }
}
