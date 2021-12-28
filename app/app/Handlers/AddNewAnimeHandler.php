<?php

namespace App\Handlers;

use App\Exceptions\Parsers\InvalidUrlException;
use App\Exceptions\Parsers\UndefinedAnimeParserException;
use App\Factories\ParserFactory;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Ramsey\Uuid\Lazy\LazyUuidFromString;
use WeStacks\TeleBot\Interfaces\UpdateHandler;
use WeStacks\TeleBot\Objects\Update;
use WeStacks\TeleBot\TeleBot;
use App\Enums\KeyboardEnum;
use App\Enums\AnimeHandlerEnum;
use App\Enums\CallbackQueryEnum;

/**
 * Class AddNewAnimeHandler
 * @package App\Handlers
 */
class AddNewAnimeHandler extends UpdateHandler
{
    private ParserFactory $parserFactory;

    public function __construct(TeleBot $bot, Update $update)
    {
        parent::__construct($bot, $update);

        $this->parserFactory = app(ParserFactory::class);
    }

    /**
     * @param Update $update
     * @param TeleBot $bot
     * @return bool
     */
    public static function trigger(Update $update, TeleBot $bot): bool
    {
        return end(CommandHandler::$executedCommands) === KeyboardEnum::ADD_NEW_TITLE->value;
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        try {
            $message = $this->update->message;

            if ($message->text !== KeyboardEnum::ADD_NEW_TITLE->value) {
                $data = [
                    'url' => $message->text
                ];

                if ($validated = $this->makeUrlValidator($data)) {
                    $this->sendMessage([
                        'text' => AnimeHandlerEnum::STARTED_PARSE_MESSAGE->value,
                    ]);

                    $anime = $this->parserFactory->getParser($validated['url'])
                        ->parse($validated['url'], $message->from->id);

                    if ($anime) {
                        $animeId = $anime instanceof LazyUuidFromString ? $anime->id->toString() : $anime->id;

                        $this->sendMessage([
                            'text' => AnimeHandlerEnum::PARSE_HAS_ENDED->value,
                            'reply_markup' => [
                                'inline_keyboard' => [
                                    [
                                        [
                                            'text' => AnimeHandlerEnum::WATCH_RECENTLY_ADDED_ANIME->value,
                                            'callback_data' => sprintf(
                                                '%s,%s',
                                                CallbackQueryEnum::CHECK_ADDED_ANIME->value,
                                                $animeId,
                                            ),
                                        ]
                                    ]
                                ]
                            ]
                        ]);

                        CommandHandler::$executedCommands = [];
                    }
                }
            }
        } catch (ValidationException $exception) {
            $this->sendMessage([
                'text' => $exception->validator->errors()->first(),
            ]);
        } catch (UndefinedAnimeParserException | InvalidUrlException | GuzzleException $exception) {
            $this->sendMessage([
                'text' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * @param array $data
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    private function makeUrlValidator(array $data): array
    {
        return Validator::make($data, [
            'url' => 'required|url',
        ], [
            'url' => AnimeHandlerEnum::INVALID_URL->value
        ])->validate();
    }
}
