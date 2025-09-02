<?php

namespace julio101290\boilerplatesells\Models;

use CodeIgniter\Model;

class SettingsMailModel extends Model
{
    protected $table      = 'mailsettings';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['id'
                                ,'idEmpresa'
                                , 'email'
                                , 'host'
                                , 'smtpDebug'
                                , 'SMTPAuth'
                                , 'port'
                                , 'created_at'
                                , 'deleted_at'
                                , 'updated_at'
                                , 'smptSecurity'
                                , 'pass'];
}