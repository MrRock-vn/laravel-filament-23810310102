<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Category;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationLabel = 'Sản phẩm';

    protected static ?string $modelLabel = 'Sản phẩm';

    protected static ?string $pluralModelLabel = 'Sản phẩm';

    /**
     * Slug của resource bắt đầu bằng MSSV
     */
    protected static ?string $slug = '23810310102-products';

    protected static ?int $navigationSort = 2;

    // ─── FORM ────────────────────────────────────────────────────────────────

    public static function form(Form $form): Form
    {
        return $form->schema([

            // ── Cột trái (2/3) ──────────────────────────────────────────────
            Forms\Components\Group::make()->schema([

                Forms\Components\Section::make('Thông tin cơ bản')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Tên sản phẩm')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                                if ($operation === 'create') {
                                    $set('slug', Str::slug($state));
                                }
                            }),

                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->unique(Product::class, 'slug', ignoreRecord: true)
                            ->maxLength(255)
                            ->dehydrateStateUsing(fn ($state) => Str::slug($state)),

                        // Rich Editor cho mô tả
                        Forms\Components\RichEditor::make('description')
                            ->label('Mô tả sản phẩm')
                            ->toolbarButtons([
                                'bold', 'italic', 'underline', 'strike',
                                'h2', 'h3',
                                'bulletList', 'orderedList',
                                'link',
                                'blockquote',
                                'undo', 'redo',
                            ])
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Giá & Tồn kho')
                    ->schema([
                        Forms\Components\TextInput::make('price')
                            ->label('Giá (VNĐ)')
                            ->required()
                            ->numeric()
                            ->minValue(0)           // Giá không được âm
                            ->step(1000)
                            ->suffix('₫')
                            ->rule('min:0'),

                        Forms\Components\TextInput::make('stock_quantity')
                            ->label('Số lượng tồn kho')
                            ->required()
                            ->numeric()
                            ->integer()             // Phải là số nguyên
                            ->minValue(0)
                            ->rule('integer'),

                        // ── Trường sáng tạo: Phần trăm giảm giá ─────────────
                        Forms\Components\TextInput::make('discount_percent')
                            ->label('Giảm giá (%)')
                            ->helperText('Nhập 0–100. Giá sau giảm sẽ hiển thị trong bảng sản phẩm.')
                            ->required()
                            ->numeric()
                            ->integer()
                            ->minValue(0)
                            ->maxValue(100)
                            ->default(0)
                            ->suffix('%')
                            ->rules(['integer', 'min:0', 'max:100']),
                    ])
                    ->columns(3),

            ])->columnSpan(2),

            // ── Cột phải (1/3) ──────────────────────────────────────────────
            Forms\Components\Group::make()->schema([

                Forms\Components\Section::make('Phân loại')
                    ->schema([
                        Forms\Components\Select::make('category_id')
                            ->label('Danh mục')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, Forms\Set $set)
                                        => $set('slug', Str::slug($state))),
                                Forms\Components\TextInput::make('slug')
                                    ->required(),
                                Forms\Components\Toggle::make('is_visible')
                                    ->default(true),
                            ]),

                        Forms\Components\Select::make('status')
                            ->label('Trạng thái')
                            ->options([
                                'draft'        => '📝 Nháp',
                                'published'    => '✅ Đã đăng',
                                'out_of_stock' => '❌ Hết hàng',
                            ])
                            ->default('draft')
                            ->required()
                            ->native(false),
                    ]),

                // Upload ảnh đại diện
                Forms\Components\Section::make('Hình ảnh')
                    ->schema([
                        Forms\Components\FileUpload::make('image_path')
                            ->label('Ảnh đại diện')
                            ->image()
                            ->imageEditor()
                            ->directory('products')
                            ->disk('public')
                            ->visibility('public')
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->helperText('JPG, PNG, WebP – tối đa 2 MB'),
                    ]),

            ])->columnSpan(1),

        ])->columns(3); // Grid layout 3 cột
    }

    // ─── TABLE ───────────────────────────────────────────────────────────────

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Ảnh')
                    ->disk('public')
                    ->square()
                    ->size(56),

                Tables\Columns\TextColumn::make('name')
                    ->label('Tên sản phẩm')
                    ->searchable()          // Tìm kiếm theo tên
                    ->sortable()
                    ->limit(40),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Danh mục')
                    ->sortable()
                    ->badge(),

                // Hiển thị giá theo định dạng VNĐ
                Tables\Columns\TextColumn::make('price')
                    ->label('Giá gốc')
                    ->formatStateUsing(fn (int $state): string =>
                        number_format($state, 0, ',', '.') . ' ₫'
                    )
                    ->sortable(),

                // Giá sau khi giảm (trường sáng tạo)
                Tables\Columns\TextColumn::make('discount_percent')
                    ->label('Giảm giá')
                    ->formatStateUsing(fn (int $state): string =>
                        $state > 0 ? "-{$state}%" : '—'
                    )
                    ->color(fn (int $state): string =>
                        $state > 0 ? 'danger' : 'gray'
                    )
                    ->badge(),

                Tables\Columns\TextColumn::make('stock_quantity')
                    ->label('Tồn kho')
                    ->sortable()
                    ->color(fn (int $state): string =>
                        $state === 0 ? 'danger' : ($state < 10 ? 'warning' : 'success')
                    ),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Trạng thái')
                    ->colors([
                        'gray'    => 'draft',
                        'success' => 'published',
                        'danger'  => 'out_of_stock',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'draft'        => 'Nháp',
                        'published'    => 'Đã đăng',
                        'out_of_stock' => 'Hết hàng',
                        default        => $state,
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Lọc theo danh mục
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Danh mục')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false),

                // Lọc theo trạng thái
                Tables\Filters\SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'draft'        => 'Nháp',
                        'published'    => 'Đã đăng',
                        'out_of_stock' => 'Hết hàng',
                    ])
                    ->native(false),

                // Lọc sản phẩm đang giảm giá
                Tables\Filters\Filter::make('on_sale')
                    ->label('Đang giảm giá')
                    ->query(fn (Builder $query) => $query->where('discount_percent', '>', 0)),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    // ─── PAGES ───────────────────────────────────────────────────────────────

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit'   => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
