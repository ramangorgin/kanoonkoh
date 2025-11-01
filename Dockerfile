# ---------- Base Image ----------
FROM php:8.2-fpm

# نصب پیش‌نیازهای PHP و اکستنشن‌های لاراول
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev libzip-dev nodejs npm \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
 && rm -rf /var/lib/apt/lists/*

 RUN docker-php-ext-install pdo pdo_mysql

# نصب Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# تنظیم مسیر کاری
WORKDIR /var/www

# کپی کل پروژه
COPY . /var/www

# تنظیم مجوزها برای پوشه‌های مهم
RUN chown -R www-data:www-data /var/www \
 && find /var/www/storage -type d -exec chmod 775 {} \; \
 && find /var/www/bootstrap/cache -type d -exec chmod 775 {} \;

# پورت PHP-FPM
EXPOSE 9000

# اجرای PHP-FPM
CMD ["php-fpm"]


