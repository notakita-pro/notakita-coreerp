#!/bin/bash

# ==========================================================
# CoreERP Project Snapshot
# Version : 1.0
# ==========================================================

OUTPUT="snapshot_$(date +%Y%m%d_%H%M%S).txt"

{
echo "============================================================"
echo "                COREERP PROJECT SNAPSHOT"
echo "============================================================"

echo ""
echo "Generated    : $(date)"
echo "Hostname     : $(hostname)"
echo "Directory    : $(pwd)"

echo ""
echo "============================================================"
echo "SYSTEM"
echo "============================================================"

echo "OS           : $(uname -a)"

echo ""
echo "PHP VERSION"
php -v | head -n 1

echo ""
echo "LARAVEL VERSION"
php artisan --version

echo ""
echo "============================================================"
echo "LARAVEL ABOUT"
echo "============================================================"

php artisan about

echo ""
echo "============================================================"
echo "PROJECT STATISTICS"
echo "============================================================"

echo "Controllers  : $(find app/Http/Controllers -name '*.php' | wc -l)"
echo "Services     : $(find app/Services -name '*.php' | wc -l)"
echo "Models       : $(find app/Models -name '*.php' | wc -l)"
echo "Jobs         : $(find app/Jobs -name '*.php' | wc -l)"
echo "Middleware   : $(find app/Http/Middleware -name '*.php' | wc -l)"
echo "Exports      : $(find app/Exports -name '*.php' | wc -l)"
echo "DTO          : $(find app/DTO -name '*.php' | wc -l)"
echo "Support      : $(find app/Support -name '*.php' | wc -l)"
echo "Views        : $(find resources/views -name '*.blade.php' | wc -l)"
echo "Routes Files : $(find routes -name '*.php' | wc -l)"
echo "Migrations   : $(find database/migrations -name '*.php' | wc -l)"

echo ""

echo "============================================================"
echo "GIT STATUS"
echo "============================================================"

git status --short 2>/dev/null || echo "Git repository tidak ditemukan."

echo ""
echo "============================================================"
echo "ROUTE SUMMARY"
echo "============================================================"

php artisan route:list 2>/dev/null | tail -n +1

echo ""
echo "============================================================"
echo "PROJECT STRUCTURE"
echo "============================================================"

tree \
-I 'vendor|node_modules|storage|bootstrap/cache|.git' \
-L 4

echo ""
echo "============================================================"
echo "END OF SNAPSHOT"
echo "============================================================"

} > "$OUTPUT"

echo ""
echo "=========================================="
echo " Snapshot berhasil dibuat"
echo "=========================================="
echo "$OUTPUT"
echo ""