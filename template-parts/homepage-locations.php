<div class="container-fluid bg-success text-white py-4">
    <div class="container">
        <h2 class="text-center">Locations We Serve Near Grand Rapids Michigan</h2>
    </div>
</div>

<div class="parallax-container">
    <div class="parallax-content d-flex align-items-center">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-10 col-lg-8">
                    <div class="d-flex justify-content-center">
                        <div class="row row-cols-1 row-cols-md-2 custom-gutter">
                            <div class="col">
                                <ul class="locations-list">
                                    <li>Wyoming, MI</li>
                                    <li>Kentwood, MI</li>
                                    <li>East Grand Rapids, MI</li>
                                    <li>Walker, MI</li>
                                    <li>Grandville, MI</li>
                                    <li>Rockford, MI</li>
                                    <li>Lowell, MI</li>
                                    <li>Jenison, MI</li>
                                    <li>Byron Center, MI</li>
                                    <li>Caledonia, MI</li>
                                </ul>
                            </div>
                            <div class="col">
                                <ul class="locations-list">
                                    <li>Sparta, MI</li>
                                    <li>Coopersville, MI</li>
                                    <li>Hudsonville, MI</li>
                                    <li>Holland, MI</li>
                                    <li>Zeeland, MI</li>
                                    <li>Allegan, MI</li>
                                    <li>Greenville, MI</li>
                                    <li>Ionia, MI</li>
                                    <li>Hastings, MI</li>
                                    <li>Cedar Springs, MI</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.parallax-container {
    position: relative;
    overflow: hidden;
}

.parallax-container::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: url('https://via.placeholder.com/1920x1080.png?text=Grand+Rapids+Skyline');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    z-index: -1;
}

.parallax-content {
    position: relative;
    z-index: 1;
    background-color: rgba(0, 0, 0, 0.7);
    padding: 30px 20px; /* Reduced from 40px to 30px */
    min-height: 100%;
}

.custom-gutter {
    margin-left: -80px;
    margin-right: -80px;
}

.custom-gutter > .col,
.custom-gutter > [class*="col-"] {
    padding-left: 80px;
    padding-right: 80px;
}

.locations-list {
    padding-left: 20px;
    margin-bottom: 0;
    margin-top: 0;
}

.locations-list li {
    margin-bottom: 3px; /* Reduced from 5px to 3px */
    font-size: 20px;
    list-style-type: disc;
    color: white;
    font-weight: 600;
}

.locations-list li::marker {
    color: white;
    font-size: 1.2em;
}

@media (max-width: 767px) {
    .custom-gutter {
        margin-left: -15px;
        margin-right: -15px;
    }

    .custom-gutter > .col,
    .custom-gutter > [class*="col-"] {
        padding-left: 15px;
        padding-right: 15px;
    }

    .locations-list li {
        font-size: 18px;
    }
}
</style>
