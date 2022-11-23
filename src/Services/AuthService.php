<?php declare(strict_types = 1);

namespace Storekeeper\AssesFullstackApi\Services;

use Storekeeper\AssesFullstackApi\Helpers\OrderHelper;
use Storekeeper\AssesFullstackApi\Repositories\UserRepository;

class AuthService
{
    private $userRepo;

    public function __construct(
        UserRepository $userRepo
    ) {
        $this->userRepo = $userRepo;
    }

    public function login($loginData)
    {
        $loginDataArray = OrderHelper::objectToArray($loginData);
        $user = $loginDataArray['user'];
        $fetchedUser = $this->userRepo->getUserByName($user);

        if ($fetchedUser == null) {
            $userFields = ['name'];
            $userValues = [$user];

            $fetchedUser = $this->userRepo->createUser($userFields, $userValues);
        } 

        $fetchedUser['authorized'] = true;
        return $fetchedUser;
    }
}
