<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'identifier' => (int) $user->id,
            'userName' => (string) $user->name,
            'userEmail' => (string) $user->email,
            'userVerified' => (int) $user->verified,
            'userAdmin' => (boolean) $user->admin,
            'createDate' => (string) $user->created_at,
            'updateDate' => (string) $user->updated_at,
            'deleteDate' => isset($user->deleted_at) ? (string) $user->deleted_at : null,

            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('users.show', $user->id),
                ],
            ],
        ];
    }

    public static function originalAttribute(string $index)
    {
        $attributes = [
            'identifier' => 'id',
            'userName' => 'name',
            'userEmail' => 'email',
            'userVerified' => 'verified',
            'userAdmin' => 'admin',
            'createDate' => 'created_at',
            'updateDate' => 'updated_at',
            'deleteDate' => 'deleted_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }

    public static function transformAttribute(string $index)
    {
        $attributes = [
            'id' => 'identifier',
            'name' => 'userName',
            'email' => 'userEmail',
            'verified' => 'userVerified',
            'admin' => 'userAdmin',
            'created_at' => 'createDate',
            'updated_at' => 'updateDate',
            'deleted_at' => 'deleteDate',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
