#!/bin/bash
# Created by Mustafa Satılmış (@mustafao9)
TARGET="${2:-/var/www/html}"
if [ "$1" = "unlock" ]; then
    chmod -R 777 "$TARGET"
elif [ "$1" = "lock" ]; then
    chmod -R 755 "$TARGET"
    find "$TARGET" -type f -exec chmod 644 {} +
fi
