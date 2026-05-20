<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class MentionParser
{
    /**
     * Extract @mentions from post body using simple regex.
     * Returns array of usernames mentioned (e.g., ['john_doe', 'jane_smith']).
     */
    public static function extractMentions(string $body): array
    {
        // Match @username pattern: @ followed by word characters (letters, digits, underscore)
        if (! preg_match_all('/@(\w+)/', $body, $matches)) {
            return [];
        }

        // $matches[1] contains captured groups (the usernames without @)
        $usernames = array_unique($matches[1]);

        return array_values($usernames); // Re-index array
    }

    /**
     * Resolve usernames to User models.
     * Returns collection of users; ignores non-existent usernames.
     */
    public static function resolveUsersByUsernames(array $usernames): Collection
    {
        if (empty($usernames)) {
            return new Collection;
        }

        return User::query()
            ->whereIn('username', $usernames)
            ->get();
    }

    /**
     * Parse mentions from post body and resolve to User models.
     * Convenience method combining extractMentions + resolveUsersByUsernames.
     */
    public static function parseMentions(string $body): Collection
    {
        $usernames = self::extractMentions($body);

        return self::resolveUsersByUsernames($usernames);
    }
}
