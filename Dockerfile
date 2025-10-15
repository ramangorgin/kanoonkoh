# ---------- Stage 1: Build frontend assets ----------
FROM node:18 AS node-builder
WORKDIR /app
COPY package*.json vite.config.js ./
COPY resources ./resources
# اگر فونت/عکس در public داری، لازم نیست اینجا کپی بشه؛ Vite خودش خروجی رو می‌سازه
RUN npm ci && npm run build

# ---------- Stage 2: PHP runtime ----------
FROM php:8.2-fpm

# نصب پیش‌نیازهای سیستمی و اکستنشن‌های PHP موردنیاز لاراول + dompdf/excel
RUN apt-get update && apt-get install -y \
    git curl zip unzip libzip-dev libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev libicu-dev \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
 && rm -rf /var/lib/apt/lists/*

# نصب Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# برای cache بهتر composer:
COPY composer.json ./
# اگر composer.lock داری کپی کن:
# COPY composer.lock ./

RUN composer install --no-dev --prefer-dist --no-interaction --no-progress

# حالا کل پروژه را کپی کن
COPY . /var/www

# کپی خروجی‌های Vite ساخته‌شده از استیج Node
# پیش‌فرض laravel-vite خروجی را داخل public/build می‌گذارد
COPY --from=node-builder /app/dist /var/www/public/build

# دسترسی‌ها
RUN chown -R www-data:www-data /var/www \
 && find /var/www/storage -type d -exec chmod 775 {} \; \
 && find /var/www/bootstrap/cache -type d -exec chmod 775 {} \;

# بهینه‌سازی لاراول (وقتی .env مناسب داخل کانتینر باشه)
# اگر key از قبل در .env هست، نیازی نیست؛ اگر نبود می‌سازه
RUN php artisan key:generate --force || true \
 && php artisan config:cache || true \
 && php artisan route:cache || true \
 && php artisan view:cache || true

# PHP-FPM listens on 9000
EXPOSE 9000
CMD ["php-fpm"]
