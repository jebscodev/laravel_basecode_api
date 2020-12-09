<?php

namespace App;

class Constants
{
    const HTTP_CODE_OK = 200;
    const HTTP_CODE_CREATED = 201;
    const HTTP_CODE_NO_CONTENT = 204;
    const HTTP_CODE_UNAUTHORIZED = 401;
    const HTTP_CODE_NOT_FOUND = 404;
    const HTTP_CODE_SERVER_ERROR = 500;

    const MSG_ADD_SUCCESS = 'Successfully added new entry.';
    const MSG_NO_DATA_MATCH = 'No matching data retrieved.';
    const MSG_DB_ERROR = 'Database Error.';
}
