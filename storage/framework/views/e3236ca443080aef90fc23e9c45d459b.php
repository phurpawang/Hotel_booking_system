<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bhutan Hotel Booking System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-blue-600 text-white shadow-lg">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <img src="<?php echo e(asset('images/bhbs-logo.png')); ?>" alt="BHBS" style="height: 50px; width: 50px; border-radius: 50%; object-fit: cover;">
                    <div>
                        <h1 class="text-2xl font-bold">Bhutan Hotel Booking System</h1>
                        <p class="text-sm text-blue-100">Your Gateway to Bhutan's Hospitality</p>
                    </div>
                </div>
                <nav class="flex gap-4">
                    <a href="<?php echo e(route('hotel.login')); ?>" class="bg-white text-blue-600 px-4 py-2 rounded-lg hover:bg-blue-50 transition">Hotel Login</a>
                    <a href="<?php echo e(route('guest.manage-booking')); ?>" class="bg-blue-700 text-white px-4 py-2 rounded-lg hover:bg-blue-800 transition">Manage Booking</a>
                    <a href="<?php echo e(route('hotel.register')); ?>" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition">Register Your Hotel</a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Hero Section with Search -->
    <section class="bg-gradient-to-r from-blue-500 to-blue-700 text-white py-16">
        <div class="text-center mb-8 container mx-auto px-4">
            <h2 class="text-4xl font-bold mb-4">Find Your Perfect Stay in Bhutan</h2>
            <p class="text-xl text-blue-100">Explore rated hotels across all Dzongkhags</p>
        </div>

        <!-- Reusable Search Bar Component -->
        <?php echo $__env->make('components.search-bar', [
            'dzongkhags' => $dzongkhags,
            'sticky' => false,
            'check_in' => request('check_in'),
            'check_out' => request('check_out'),
            'adults' => request('adults', 1),
            'children' => request('children', 0),
            'rooms' => request('rooms', 1),
            'dzongkhag_id' => request('dzongkhag_id')
        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </section>

    <!-- Active Promotions Section -->
    <?php
        // Check if any featured hotel has active promotions
        $hasPromotions = false;
        foreach($featuredHotels as $hotel) {
            if($hotel->promotions && count($hotel->promotions) > 0) {
                $hasPromotions = true;
                break;
            }
        }
    ?>
    
    <?php if($hasPromotions): ?>
    <section class="bg-gradient-to-r from-orange-50 to-yellow-50 py-12">
        <div class="container mx-auto px-4">
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">
                    <i class="fas fa-tag text-orange-600 mr-2"></i> Current Promotions
                </h2>
                <p class="text-gray-600">Save on your next stay in Bhutan</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php $__currentLoopData = $featuredHotels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hotel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($hotel->promotions && count($hotel->promotions) > 0): ?>
                        <?php $__currentLoopData = $hotel->promotions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $promotion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="bg-white rounded-xl shadow-md border-l-4 border-orange-500 overflow-hidden hover:shadow-lg transition">
                                <div class="bg-gradient-to-r from-orange-500 to-red-500 p-4">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-lg font-bold text-white"><?php echo e($promotion->title); ?></h3>
                                        <div class="bg-white rounded-full px-3 py-1 ml-2">
                                            <span class="text-2xl font-bold text-orange-600">
                                                <?php if($promotion->discount_type === 'percentage'): ?>
                                                    <?php echo e($promotion->discount_value); ?>%
                                                <?php else: ?>
                                                    Nu.<?php echo e(number_format($promotion->discount_value, 0)); ?>

                                                <?php endif; ?>
                                            </span>
                                            <span class="text-xs text-gray-600 block">OFF</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-4">
                                    <p class="text-sm text-blue-600 font-semibold mb-2">
                                        <i class="fas fa-hotel mr-1"></i> <?php echo e($hotel->name); ?>

                                    </p>
                                    <?php if($promotion->description): ?>
                                        <p class="text-gray-700 text-sm mb-3"><?php echo e($promotion->description); ?></p>
                                    <?php endif; ?>
                                    <div class="space-y-2 text-sm text-gray-600">
                                        <p><i class="fas fa-bed mr-2 text-blue-500"></i><span class="font-semibold">Applies to:</span> <?php echo e($promotion->getAppliesTo()); ?></p>
                                        <p><i class="fas fa-calendar mr-2 text-green-500"></i><span class="font-semibold">Valid until:</span> <?php echo e($promotion->end_date->format('M d, Y')); ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Top Rated Hotels -->
    <section class="container mx-auto px-4 py-12">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h3 class="text-3xl font-bold text-gray-800">Top Rated Hotels</h3>
                <p class="text-gray-600">Highly rated by our guests</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php $__empty_1 = true; $__currentLoopData = $featuredHotels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hotel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition">
                <div class="h-48 relative">
                    <?php if($hotel->property_image): ?>
                        <img src="<?php echo e(asset('storage/' . $hotel->property_image)); ?>" alt="<?php echo e($hotel->name); ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <div class="w-full h-full bg-blue-200 flex items-center justify-center">
                            <i class="fas fa-hotel text-white text-5xl"></i>
                        </div>
                    <?php endif; ?>
                    <div class="absolute top-4 right-4 bg-white px-3 py-1 rounded-full text-sm font-semibold text-gray-700">
                        <?php if($hotel->star_rating): ?>
                            <?php for($i = 0; $i < $hotel->star_rating; $i++): ?>
                                <i class="fas fa-star text-yellow-400"></i>
                            <?php endfor; ?>
                        <?php else: ?>
                            <span class="text-gray-500">Not Rated</span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="p-6">
                    <h4 class="text-xl font-bold text-gray-800 mb-2"><?php echo e($hotel->name); ?></h4>
                    <p class="text-gray-600 mb-2">
                        <i class="fas fa-map-marker-alt text-blue-600"></i> 
                        <?php echo e($hotel->dzongkhag ? $hotel->dzongkhag . ', ' : ''); ?>Bhutan
                    </p>
                    <p class="text-gray-700 mb-4 text-sm line-clamp-2">
                        <?php echo e($hotel->description ?? 'A wonderful place to stay in Bhutan'); ?>

                    </p>
                    <div class="flex justify-between items-center">
                        <div class="text-gray-700 font-semibold">
                            From Nu. <?php echo e(number_format($hotel->rooms->min('price_per_night') ?? 0, 2)); ?>/night
                        </div>
                        <a href="<?php echo e(route('guest.hotel', $hotel->id)); ?>" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-3 text-center py-12">
                <p class="text-gray-600 text-lg">No hotels available at the moment.</p>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- All Registered Hotels -->
    <section class="container mx-auto px-4 py-12 bg-gray-100 rounded-xl">
        <div class="mb-8">
            <h3 class="text-3xl font-bold text-gray-800 mb-2">All Registered Hotels</h3>
            <p class="text-gray-600">Browse every active listing</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php $__empty_1 = true; $__currentLoopData = $featuredHotels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hotel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition">
                <div class="h-48 relative">
                    <?php if($hotel->property_image): ?>
                        <img src="<?php echo e(asset('storage/' . $hotel->property_image)); ?>" alt="<?php echo e($hotel->name); ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                            <i class="fas fa-hotel text-gray-400 text-5xl"></i>
                        </div>
                    <?php endif; ?>
                    <div class="absolute top-4 right-4 bg-white px-3 py-1 rounded-full">
                        <?php if($hotel->star_rating): ?>
                            <?php for($i = 0; $i < $hotel->star_rating; $i++): ?>
                                <i class="fas fa-star text-yellow-400"></i>
                            <?php endfor; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="p-6">
                    <h4 class="text-xl font-bold text-gray-800 mb-2"><?php echo e($hotel->name); ?></h4>
                    <p class="text-gray-600 mb-2">
                        <i class="fas fa-map-marker-alt text-blue-600"></i> 
                        <?php echo e($hotel->dzongkhag ? $hotel->dzongkhag . ', ' : ''); ?>Bhutan
                    </p>
                    <p class="text-gray-700 mb-4 text-sm line-clamp-2">
                        <?php echo e($hotel->description ?? 'Experience authentic Bhutanese hospitality'); ?>

                    </p>
                    <a href="<?php echo e(route('guest.hotel', $hotel->id)); ?>" class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition w-full text-center">
                        View Details
                    </a>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-3 text-center py-12">
                <p class="text-gray-600 text-lg">No hotels registered yet.</p>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12 mt-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Support -->
                <div>
                    <h5 class="text-lg font-bold mb-4">Support</h5>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-300 hover:text-white">Manage your login</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Contact Customer Service</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Safety Resource Center</a></li>
                    </ul>
                </div>

                <!-- Discover -->
                <div>
                    <h5 class="text-lg font-bold mb-4">Discover</h5>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-300 hover:text-white">Getting loyalty program</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Seasonal and holiday deals</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Travel articles</a></li>
                    </ul>
                </div>

                <!-- Terms and Settings -->
                <div>
                    <h5 class="text-lg font-bold mb-4">Terms and settings</h5>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-300 hover:text-white">Privacy Notice</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Terms of Service</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Accessibility Statement</a></li>
                    </ul>
                </div>

                <!-- Partners -->
                <div>
                    <h5 class="text-lg font-bold mb-4">Partners</h5>
                    <ul class="space-y-2">
                        <li><a href="<?php echo e(route('hotel.register')); ?>" class="text-gray-300 hover:text-white">Extranet login</a></li>
                        <li><a href="<?php echo e(route('hotel.register')); ?>" class="text-gray-300 hover:text-white">Partner help</a></li>
                        <li><a href="<?php echo e(route('hotel.register')); ?>" class="text-gray-300 hover:text-white">List your property</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-700 mt-8 pt-8 text-center">
                <p class="text-gray-400">&copy; 2026 Bhutan Hotel Booking System. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
    // Additional page-specific scripts can be added here if needed
    </script>
</body>
</html>
<?php /**PATH C:\XAMPP\htdocs\BHBS\resources\views/guest/home.blade.php ENDPATH**/ ?>