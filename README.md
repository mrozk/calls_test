Инсталяция приложения 
============================

1. Настроить веб-сервер на директорию web/
2. Настроить доступы к БД в файле config.php, пример config-example
3. Миграция index.php?r=main/migrate
4. web/assets/app/img/ Доступ на запись через PHP


Базовая функциональность
============================
1) Сделать форму обратной связи. 
На странице должны быть показаны все оставленные отзывы, под ними форма: Имя, E-mail, текст сообщения, кнопки "Предварительный просмотр" и "Отправить".
Отзывы можно сортировать по имени автора, e-mail и дате добавления (по умолчанию - по дате, последние наверху).Также должна быть валидация.
2) Предварительный просмотр должен работать без перезагрузки страницы.
3) Сделать вход для администратора (логин "admin", пароль "123"). Администратор должен иметь возможность редактировать отзыв. Измененные отзывы в общем списке выводятся с пометкой "изменен администратором".
4) К отзыву можно прикрепить картинку.
Картинка должна быть не более 320х240 пикселей, при попытке залить изображение большего размера, картинка должна быть пропорционально уменьшена до заданных размеров. Допустимые форматы: JPG, GIF, PNG.
5) У администратора должна быть возможность модерирования.
Т.е. на странице администратора показаны отзывы с миниатюрами картинок и их статусы (принят/отклонен).
Отзыв становится видимым для всех только после принятия админом. Отклоненные отзывы остаются в базе, но не показываются обычным пользователям. Изменение картинки администратором не требуется.

В приложении нужно с помощью чистого PHP реализовать модель MVC (PHP-фреймворки использовать нельзя).
Верстка на bootstrap. Помните, что аккуратность - это один из главных критериев оценки тестового.

Приложение нужно развернуть на любом бесплатном хостинге, чтобы можно было посмотреть его в действии. 
Скопируйте в корневую папку проекта наш онлайн-редактор dayside (https://github.com/boomyjee/dayside)
Таким образом редактор будет доступен по url <ваш проект>/dayside/index.php
Убедитесь, что настройки .htaccess подволяют редактору открыться. При первом запуске редактор попросит установить пароль,  поставьте как в админке: 123.
