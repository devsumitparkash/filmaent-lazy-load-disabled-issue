<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Enums\OrderStatus;
use App\Models\Business\Order\Order;
use App\Filament\Resources\OrderResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Filament\Resources\Pages\ViewRecord;
use Livewire\Component;
use Filament\Actions\Action;
use Filament\Actions;
use Filament\Notifications\Notification;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\EditAction::make(),

            Actions\Action::make('update-order-status')
            ->mountUsing(function (Action $action) {
                    $this->record->load([
                        'items' => fn ($query) => $query->chaperone(),
                        'totals' => fn ($query) => $query->chaperone(),
                    ]);
            })
            ->label('Update Status')
            ->icon('heroicon-o-truck')
            ->color('success')
            ->form([
                        \Filament\Forms\Components\Select::make('status')
                        ->options(
                            collect(OrderStatus::cases())->mapWithKeys(fn ($case) => [
                                $case->value => $case->getLabel(),
                            ])->toArray(),
                        )
                        ->default(fn (Order $record) => $record->status->value)
                        ->required()
            ])

            //->visible(fn ($record) => $record->status !== 'shipped')
            ->action(function (Component $livewire, Order $record, Action $action, array $data) {


               $record->update(['status' => $data['status']]);

                Notification::make()
                    ->title('Order status updated!')
                    ->success()
                    ->send();

                    //$this->record->refresh();
                    //$this->refreshFormData([]);

                    $action->success();
            })
            //->successRedirectUrl(fn () => $this->getResource()::getUrl('view', ['record' => $this->record]))
            ,
        ];
    }

    public function mount(int|string $record): void
    {
        parent::mount($record);

        $this->record->load([
            'items' => fn ($items) => $items->chaperone(),
            'totals' => fn ($totals) => $totals->chaperone(),
        ]);
    }

    /*
    public function resolveRecord($key): Model
    {
        $record = static::getResource()::resolveRecordRouteBinding($key);

        if ($record === null) {
            throw (new ModelNotFoundException)->setModel($this->getModel(), [$key]);
        }

        $record->load([
            'items.order',
            'totals.order',


            // 'items' => fn ($items) => $items->chaperone(),
            // 'totals' => fn ($totals) => $totals->chaperone(),

        ]);

        return $record;
    }
    */

}
