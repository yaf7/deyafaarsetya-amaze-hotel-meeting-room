<x-app-layout>
    <!-- Header Section -->
    <section class="bg-gradient-hero py-16 relative overflow-hidden">
        <div class="floating-decoration w-64 h-64 bg-white/10 -top-10 -right-10"></div>
        <div class="floating-decoration w-48 h-48 bg-secondary-500/20 bottom-0 left-10"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center">
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">Hubungi Kami</h1>
                <p class="text-white/80 text-lg max-w-2xl mx-auto">
                    Kami siap membantu Anda merencanakan meeting yang sempurna
                </p>
            </div>
        </div>

        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 60L60 50C120 40 240 20 360 15C480 10 600 20 720 25C840 30 960 30 1080 25C1200 20 1320 10 1380 5L1440 0V60H0Z" fill="#f9fafb"/>
            </svg>
        </div>
    </section>

    <!-- Contact Content -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">

                <!-- Contact Info -->
                <div class="space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">Informasi Kontak</h2>

                        <div class="space-y-5">
                            <!-- Address -->
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-map-marker-alt text-amber-600 text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-800 mb-1">Alamat</h3>
                                    <a href="https://maps.app.goo.gl/D6EBq8XXiza2keoQ8" target="_blank" class="text-gray-600 hover:text-amber-600 transition-colors">
                                        Jl. Raden Ajeng Kartini No.69, Doko,<br>
                                        Kec. Ngasem, Kabupaten Kediri,<br>
                                        Jawa Timur 64182
                                    </a>
                                </div>
                            </div>

                            <!-- Phone / WhatsApp -->
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <i class="fab fa-whatsapp text-green-600 text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-800 mb-1">WhatsApp</h3>
                                    <a href="https://wa.me/6281333753065" target="_blank" class="text-gray-600 hover:text-green-600 transition-colors text-lg font-medium">
                                        0813-3375-3065
                                    </a>
                                    <p class="text-sm text-gray-500 mt-1">Klik untuk chat langsung</p>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-envelope text-blue-600 text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-800 mb-1">Email</h3>
                                    <a href="mailto:info@amazehotel.com" class="text-gray-600 hover:text-blue-600 transition-colors">
                                        amazehotelkediri@gmail.com
                                    </a>
                                </div>
                            </div>

                            <!-- Hours -->
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-clock text-purple-600 text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-800 mb-1">Jam Operasional</h3>
                                    <p class="text-gray-600">Senin - Minggu</p>
                                    <p class="text-gray-600 font-medium">24 Jam</p>
                                </div>
                            </div>
                        </div>

                        <!-- CTA Button -->
                        <div class="mt-8 pt-6 border-t border-gray-100">
                            <a href="https://wa.me/6281333753065" target="_blank"
                               class="w-full inline-flex items-center justify-center gap-3 bg-green-500 hover:bg-green-600 text-white font-semibold py-4 rounded-xl transition-colors shadow-lg shadow-green-500/30">
                                <i class="fab fa-whatsapp text-xl"></i>
                                <span>Chat via WhatsApp</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Map Section -->
                <div class="space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 sm:p-8 border-b border-gray-100">
                            <h2 class="text-2xl font-bold text-gray-800">Lokasi Kami</h2>
                            <p class="text-gray-500 mt-1">Amaze Hotel Kediri</p>
                        </div>

                        <!-- Google Maps Embed -->
                        <div class="aspect-video">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3953.0775!2d112.0189!3d-7.8247!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e78570e4b8c01e1%3A0x7e3f3f3f3f3f3f3f!2sJl.%20Raden%20Ajeng%20Kartini%20No.69%2C%20Doko%2C%20Kec.%20Ngasem%2C%20Kabupaten%20Kediri%2C%20Jawa%20Timur%2064182!5e0!3m2!1sen!2sid!4v1705000000000"
                                width="100%"
                                height="100%"
                                style="border:0;"
                                allowfullscreen=""
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>

                        <div class="p-4 sm:p-6">
                            <a href="https://maps.app.goo.gl/D6EBq8XXiza2keoQ8" target="_blank"
                               class="w-full inline-flex items-center justify-center gap-2 bg-amber-500 hover:bg-amber-600 text-white font-medium py-3 rounded-xl transition-colors">
                                <i class="fas fa-directions"></i>
                                <span>Buka di Google Maps</span>
                            </a>
                        </div>
                    </div>

                    <!-- Additional Info Card -->
                    <div class="bg-gradient-to-br from-amber-500 to-orange-500 rounded-2xl p-6 sm:p-8 text-white">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                                <i class="fas fa-info-circle text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-bold">Butuh Bantuan?</h3>
                        </div>
                        <p class="text-white/90 mb-6">
                            Tim kami siap membantu Anda merencanakan meeting atau acara yang sempurna. Jangan ragu untuk menghubungi kami!
                        </p>
                        <a href="{{ route('rooms.index') }}"
                           class="inline-flex items-center gap-2 bg-white text-amber-600 font-semibold px-6 py-3 rounded-xl hover:bg-amber-50 transition-colors">
                            <i class="fas fa-door-open"></i>
                            <span>Lihat Ruang Meeting</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
