<?php
$carFolder = $_SERVER['DOCUMENT_ROOT'] . '/uploads/cars/';
$carFiles = array_merge(
    glob($carFolder . '*.jpg'),
    glob($carFolder . '*.jpeg'),
    glob($carFolder . '*.png'),
    glob($carFolder . '*.gif')
);


$allCars = [];

foreach ($carFiles as $file) {
    $fileName = pathinfo($file, PATHINFO_FILENAME);

    $category = 'sedan';
    if (stripos($fileName, 'tesla') !== false) {
        $category = 'electric';
    } elseif (stripos($fileName, 'bmw') !== false) {
        $category = 'luxury';
    }

    $allCars[] = [
        'name' => str_replace('_', ' ', $fileName),
        'image' => str_replace($_SERVER['DOCUMENT_ROOT'], '', $file),
        'badge' => ucfirst($category),
        'rating' => rand(3, 10) . '.' . rand(0, 9),
        'seats' => rand(2, 7),
        'transmission' => 'Automatic',
        'type' => 'Sedan',
        'price' => rand(50, 300),
        'category' => $category
    ];
}

// Handle filter request
$selectedCategory = isset($_GET['category']) ? $_GET['category'] : 'all';
$filteredCars     = $selectedCategory === 'all'
    ? $allCars
    : array_filter($allCars, function ($car) use ($selectedCategory) {
        return $car['category'] === $selectedCategory;
    });
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DriveNow - Premium Car Rental</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 20px 0;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .logo {
            font-size: 28px;
            font-weight: 800;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-links {
            display: flex;
            gap: 30px;
            list-style: none;
        }

        .nav-links a {
            text-decoration: none;
            color: #333;
            font-weight: 600;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: #667eea;
        }

        .hero {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 30px;
            padding: 60px;
            margin: 40px 0;
            text-align: center;
            color: white;
        }

        .hero h1 {
            font-size: 56px;
            font-weight: 800;
            margin-bottom: 20px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .hero p {
            font-size: 20px;
            margin-bottom: 40px;
            opacity: 0.95;
        }

        .search-box {
            background: white;
            border-radius: 20px;
            padding: 30px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            margin-bottom: 40px;
        }

        .search-field {
            display: flex;
            flex-direction: column;
        }

        .search-field label {
            font-size: 14px;
            font-weight: 600;
            color: #666;
            margin-bottom: 8px;
        }

        .search-field input,
        .search-field select {
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        .search-field input:focus,
        .search-field select:focus {
            outline: none;
            border-color: #667eea;
        }

        .search-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 15px 40px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
            align-self: flex-end;
        }

        .search-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin: 60px 0;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 20px;
            text-align: center;
            transition: transform 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-5px);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
        }

        .feature-card h3 {
            margin-bottom: 10px;
            color: #333;
        }

        .feature-card p {
            color: #666;
            line-height: 1.6;
        }

        .category-filter {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin: 40px 0;
            flex-wrap: wrap;
        }

        .category-btn {
            padding: 12px 30px;
            background: rgba(255, 255, 255, 0.95);
            border: 2px solid transparent;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            color: #333;
        }

        .category-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .category-btn.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: white;
        }

        .car-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
            margin: 40px 0;
        }

        .car-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
            position: relative;
        }

        .car-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .car-image {
            width: 100%;
            height: 240px;
            object-fit: cover;
        }

        .car-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .car-content {
            padding: 25px;
        }

        .car-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 15px;
        }

        .car-name {
            font-size: 22px;
            font-weight: 700;
            color: #333;
        }

        .car-rating {
            display: flex;
            align-items: center;
            gap: 5px;
            background: #fef3c7;
            padding: 5px 10px;
            border-radius: 10px;
        }

        .car-specs {
            display: flex;
            gap: 15px;
            margin: 20px 0;
            flex-wrap: wrap;
        }

        .spec {
            font-size: 14px;
            color: #666;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .car-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 20px;
            border-top: 2px solid #f0f0f0;
        }

        .car-price {
            font-size: 32px;
            font-weight: 800;
            color: #667eea;
        }

        .car-price span {
            font-size: 14px;
            color: #999;
            font-weight: 400;
        }

        .rent-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .rent-btn:hover {
            transform: scale(1.05);
        }

        footer {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px 0;
            margin-top: 60px;
            text-align: center;
        }

        footer p {
            color: #666;
        }

        @media (max-width: 768px) {
            .hero h1 {
                font-size: 36px;
            }

            .nav-links {
                display: none;
            }

            .search-box {
                grid-template-columns: 1fr;
            }

            .car-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <header>
        <nav>
            <div class="logo">DriveNow</div>
            <ul class="nav-links">
                <li><a href="#home">Home</a></li>
                <li><a href="#cars">Cars</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <section class="hero">
            <h1>Find Your Perfect Ride</h1>
            <p>Premium car rental service with the best vehicles and unbeatable prices</p>
        </section>

        <div class="search-box">
            <div class="search-field">
                <label>📍 Location</label>
                <input type="text" placeholder="Enter city or airport">
            </div>
            <div class="search-field">
                <label>📅 Pick-up Date</label>
                <input type="date" value="<?php echo date('Y-m-d'); ?>">
            </div>
            <div class="search-field">
                <label>📅 Return Date</label>
                <input type="date" value="<?php echo date('Y-m-d', strtotime('+3 days')); ?>">
            </div>
            <div class="search-field">
                <button class="search-btn">Search Cars</button>
            </div>
        </div>

        <div class="features">
            <div class="feature-card">
                <div class="feature-icon">⚡</div>
                <h3>Instant Booking</h3>
                <p>Book your car in seconds with our streamlined process</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🛡️</div>
                <h3>Fully Insured</h3>
                <p>All vehicles come with comprehensive insurance coverage</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🕐</div>
                <h3>24/7 Support</h3>
                <p>Our team is always here to help you on your journey</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">💰</div>
                <h3>Best Prices</h3>
                <p>Competitive rates with no hidden fees guaranteed</p>
            </div>
        </div>

        <h2 style="text-align: center; color: white; font-size: 42px; margin: 60px 0 30px;">Featured Vehicles</h2>

        <div class="category-filter">
            <a href="?category=all" class="category-btn <?php echo $selectedCategory === 'all' ? 'active' : ''; ?>">All Cars</a>
            <a href="?category=luxury" class="category-btn <?php echo $selectedCategory === 'luxury' ? 'active' : ''; ?>">Luxury</a>
            <a href="?category=sport" class="category-btn <?php echo $selectedCategory === 'sport' ? 'active' : ''; ?>">Sport</a>
            <a href="?category=sedan" class="category-btn <?php echo $selectedCategory === 'sedan' ? 'active' : ''; ?>">Sedan</a>
            <a href="?category=electric" class="category-btn <?php echo $selectedCategory === 'electric' ? 'active' : ''; ?>">Electric</a>
        </div>

        <div class="car-grid">
            <?php foreach ($filteredCars as $car): ?>
                <div class="car-card">
                    <img src="<?php echo htmlspecialchars($car['image']); ?>" alt="<?php echo htmlspecialchars($car['name']); ?>" class="car-image">
                    <?php if (!empty($car['badge'])): ?>
                        <div class="car-badge"><?php echo htmlspecialchars($car['badge']); ?></div>
                    <?php endif; ?>
                    <div class="car-content">
                        <div class="car-header">
                            <h3 class="car-name"><?php echo htmlspecialchars($car['name']); ?></h3>
                            <div class="car-rating">⭐ <?php echo $car['rating']; ?></div>
                        </div>
                        <div class="car-specs">
                            <span class="spec">👥 <?php echo $car['seats']; ?> Seats</span>
                            <span class="spec">⚙️ <?php echo htmlspecialchars($car['transmission']); ?></span>
                            <span class="spec">🚗 <?php echo htmlspecialchars($car['type']); ?></span>
                        </div>
                        <div class="car-footer">
                            <div class="car-price">$<?php echo $car['price']; ?><span>/day</span></div>
                            <button class="rent-btn">Rent Now</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>

    <footer>
        <div class="container">
            <p>&copy; 2025 DriveNow. All rights reserved. | Premium Car Rental Service</p>
        </div>
    </footer>
</body>

</html>