public function up(): void
{
    Schema::create('feedbacks', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('book_id')->nullable()->constrained()->onDelete('cascade'); // Untuk rating buku spesifik
        $table->text('pesan');
        $table->integer('rating')->default(5); // Kolom bintang 1-5
        $table->enum('kategori', ['saran', 'kritik', 'lainnya']);
        $table->timestamps();
    });
}