<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\Controller\Traits;

use Symfony\Component\HttpFoundation\Request;

trait LimitOffsetTrait
{
    /**
     * Возвращает limit, offset из запроса.
     *
     * @return array ['limit', 'offset']
     */
    public function getLimitOffset(Request $request): array
    {
        $limit = $request->get('limit') ?? $this->getParameter('app.max_items');
        $offset = $request->get('offset') ?? 0;

        return [$limit, $offset];
    }
}
