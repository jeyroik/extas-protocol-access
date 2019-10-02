# extas-protocol-access
Access protocol package for Extas

Парсит параметры запроса и его заголовки на предмет наличия параметров доступа (секция, субъект и операция).

Для заголовков префикс по умолчанию `x-extas-`, т.е. ожидаются следующие заголовки:
- `x-extas-section`
- `x-extas-subject`
- `x-extas-operation`

Переопределить префикс можно через переменную окружения `EXTAS__PROTOCOL_ACCESS__HEADER_PREFIX`.

Также через переменные окружения можно задать значения по умолчанию:

- `EXTAS__PROTOCOL_ACCESS__DEFAULT__SECTION`
- `EXTAS__PROTOCOL_ACCESS__DEFAULT__SUBJECT`
- `EXTAS__PROTOCOL_ACCESS__DEFAULT__OPERATION`