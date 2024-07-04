<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\TelegraphChat;
use DefStudio\Telegraph\Facades\Telegraph;
use Illuminate\Database\Eloquent\Model;
use App\Models\Chat;

use Illuminate\Support\Facades\Log;
use MoonShine\Fields\Date;
use MoonShine\Fields\Phone;
use MoonShine\Fields\Relationships\HasMany;
use MoonShine\Fields\Select;
use MoonShine\Fields\Text;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Field;
use MoonShine\Components\MoonShineComponent;

/**
 * @extends ModelResource<TelegraphChat>
 */
class ChatResource extends ModelResource
{
    protected string $model = TelegraphChat::class;

    protected string $title = 'Пользователи';

    /**
     * @return list<MoonShineComponent|Field>
     */
    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
                Text::make('Имя', 'name')->disabled(),
                Phone::make('Телефон', 'phone')->disabled(),
                Select::make('Статус', 'status')
                    ->options(TelegraphChat::statuses()),
                Date::make('Дата', 'created_at')->disabled(),
                HasMany::make('Messages','messages',resource: new ChatMessagesResource())
                ->hideOnIndex()
            ]),
        ];
    }

    protected function afterUpdated(Model $item): Model
    {
        if($item->wasChanged('status')){
            Telegraph::chat($item->chat_id)->message('Ваш статус изменён на: '. TelegraphChat::statuses()[$item->status])->send();
        }
        return $item;
    }

    /**
     * @param TelegraphChat $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [];
    }
}
