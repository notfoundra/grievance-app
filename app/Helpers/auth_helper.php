<?php

if (! function_exists('current_user')) {
    function current_user(): ?array
    {
        $session = session();

        if (! $session->get('logged_in')) {
            return null;
        }

        return [
            'id'      => $session->get('user_id'),
            'name'    => $session->get('user_name'),
            'role'    => $session->get('user_role'),
            'site_id' => $session->get('user_site_id'),
        ];
    }
}

if (! function_exists('has_role')) {
    function has_role(string|array $roles): bool
    {
        $current = session()->get('user_role');

        if (! $current) {
            return false;
        }

        return in_array($current, (array) $roles, true);
    }
}
if (! function_exists('scoped_site_id')) {

    function scoped_site_id(?string $requestedSiteId = null)
    {
        $user = current_user();

        if (! $user) {
            return null;
        }

        if ($user['role'] === \App\Models\UserModel::ROLE_ADMIN) {
            return $requestedSiteId ?: null;
        }

        return $user['site_id'];
    }
}

if (! function_exists('user_owns_site')) {
    function user_owns_site(?int $siteId): bool
    {
        $user = current_user();

        if (! $user) {
            return false;
        }

        if ($user['role'] === \App\Models\UserModel::ROLE_ADMIN) {
            return true;
        }

        return (int) $user['site_id'] === (int) $siteId;
    }
}
