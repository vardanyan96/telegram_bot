<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\TelegramChatMessage;
use Illuminate\Database\Eloquent\Model;
use App\Models\ChatMessages;

use MoonShine\Enums\ClickAction;
use MoonShine\Fields\Date;
use MoonShine\Fields\Text;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Field;
use MoonShine\Components\MoonShineComponent;

/**
 * @extends ModelResource<TelegramChatMessage>
 */
class ChatMessagesResource extends ModelResource
{
    protected string $model = TelegramChatMessage::class;

    protected string $title = 'ChatMessages';

    public function getActiveActions(): array
    {
        return [];
    }

    public function search(): array
    {
        return [];
    }

    /**
     * @return list<MoonShineComponent|Field>
     */
    public function fields(): array
    {
        return [
            Block::make([
                Text::make('Текст','message'),
                Date::make('Дата', 'created_at')->disabled(),
            ]),
        ];
    }

    /**
     * @param ChatMessages $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [];
    }
}
