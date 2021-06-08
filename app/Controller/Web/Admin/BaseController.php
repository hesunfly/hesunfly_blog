<?php

declare(strict_types=1);

namespace App\Controller\Web\Admin;

use App\Controller\AbstractController;
use Qbhy\HyperfAuth\AuthManager;
use Hyperf\Di\Annotation\Inject;

class BaseController extends AbstractController
{
    /**
     * @Inject
     * @var AuthManager
     */
    protected $auth;
}

