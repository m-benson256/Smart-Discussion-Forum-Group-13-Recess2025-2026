public function up(): void
{
    Schema::table('Topics', function (Blueprint $table) {
        if (!Schema::hasColumn('Topics', 'user_id')) {
            $table->foreignId('user_id')
                ->after('id')
                ->constrained('users')
                ->cascadeOnDelete();
        }

        if (!Schema::hasColumn('Topics', 'group_id')) {
            $table->foreignId('group_id')
                ->nullable()
                ->after('user_id')
                ->constrained('groups')
                ->nullOnDelete();
        }

        if (!Schema::hasColumn('Topics', 'category_id')) {
            $table->foreignId('category_id')
                ->nullable()
                ->after('group_id')
                ->constrained('categories', 'CategoryID')
                ->nullOnDelete();
        }

        if (!Schema::hasColumn('Topics', 'title')) {
            $table->string('title');
        }

        if (!Schema::hasColumn('Topics', 'content')) {
            $table->text('content');
        }
    });
}