<?php

namespace Lara;

/** Resource permissions in read, write, export, share order; a dash means absent. */
class PermissionMask
{
    const READ = 'r---';
    const READ_WRITE = 'rw--';
    const READ_EXPORT = 'r-e-';
    const READ_SHARE = 'r--s';
    const READ_WRITE_EXPORT = 'rwe-';
    const READ_WRITE_SHARE = 'rw-s';
    const READ_EXPORT_SHARE = 'r-es';
    const READ_WRITE_EXPORT_SHARE = 'rwes';
}
