<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<main class="ml-64 mt-16 p-4"> <!-- Push content to the right, starting after the navigation -->
    <!-- Welcome section of the admin dashboard -->
    <div class="bg-white shadow-lg rounded-lg p-6 mb-8">
        <h1 class="text-4xl font-bold text-center mb-4">Welcome!</h1>
        <p class="text-lg text-gray-700 text-center">
            Welcome to the administration panel. Here you can view detailed statistics on registered friends and available and sold trees. 
            Use this information to make informed decisions and manage resources efficiently.
        </p>
    </div>

    <!-- Section with flexible layout (responsive) for graphs -->
    <div class="flex flex-col lg:flex-row gap-8">
        
        <!-- Graph for displaying the distribution of friends by gender -->
        <div class="bg-white shadow-lg rounded-lg p-6 w-full lg:w-1/2">
            <h2 class="text-xl font-semibold mb-4 text-center">Distribution of Friends by Gender</h2>
            <p class="text-gray-600 mb-6 text-sm text-center">
                This graph shows the distribution of registered friends according to gender. Use this information to understand the diversity in our community of friends.
            </p>
            <div class="flex flex-col items-center">
                <!-- Pie chart showing the gender distribution -->
                <canvas id="genderChart" class="w-full h-60"></canvas>
                <div class="mt-6 text-left bg-gray-50 p-4 rounded-md w-full">
                    <h3 class="text-lg font-semibold text-center">Gender Details:</h3>
                    <div class="mt-4 flex justify-around text-gray-700">
                        <p><strong>Female:</strong> <?= $genders['F']; ?></p>
                        <p><strong>Male:</strong> <?= $genders['M']; ?></p>
                        <p><strong>Other:</strong> <?= $genders['O']; ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Graph for comparing available trees -->
        <div class="bg-white shadow-lg rounded-lg p-6 w-full lg:w-1/2">
            <h2 class="text-xl font-semibold mb-4 text-center">Comparison of Available Trees</h2>
            <p class="text-gray-600 mb-6 text-sm text-center">
                This graph compares the number of trees currently available. Useful for monitoring inventories and evaluating the popularity of our offerings.
            </p>
            <!-- Bar chart comparing available and sold trees -->
            <canvas id="treesComparisonChart" class="w-full h-60"></canvas>
        </div>
    </div>

    <!-- Additional information section about the statistics -->
    <div class="bg-blue-50 shadow rounded-lg p-6 mt-8">
        <h2 class="text-xl font-semibold text-center text-blue-600">Additional Information</h2>
        <p class="text-gray-700 mt-4">
            This statistics section allows managers to better understand the distribution of the community and the status of tree resources. The information gathered
            can assist in future management and planning decisions. It also fosters an inclusive and well-informed environment about the diversity of registered friends.
        </p>
        <p class="text-gray-700 mt-4">
            If you wish to make adjustments to the statistics displayed or update the data, you can go to the configuration section or contact technical support.
        </p>
    </div>
</main>

<!-- Script to create the bar chart for comparing available and sold trees -->
<script>
// Assigns the PHP values to JavaScript variables
const availableTreesCount = <?= $availableTreesCount; ?>;

// Gets the context for the bar chart canvas
const ctxComparison = document.getElementById('treesComparisonChart').getContext('2d');

// Creates the bar chart using Chart.js
const treesComparisonChart = new Chart(ctxComparison, {
    type: 'bar', // Specifies that the chart is a bar chart
    data: {
        labels: ['Trees'], // Labels for the X-axis
        datasets: [
            {
                label: 'Available Trees', // Label for the dataset
                data: [availableTreesCount], // Data for available trees
                backgroundColor: 'rgba(0, 255, 0, 0.5)', // Background color for the bars
                borderColor: 'rgba(0, 255, 0, 1)', // Border color for the bars
                borderWidth: 2 // Border width
            }
        ]
    },
    options: {
        responsive: true, // Makes the chart responsive
        plugins: {
            legend: {
                position: 'top', // Position of the legend
            },
            title: {
                display: true, // Display the title
                text: 'Comparison of Available Trees' // Title of the chart
            }
        },
        scales: {
            y: {
                beginAtZero: true, // Ensures that the Y-axis starts at zero
            }
        }
    }
});
</script>

<!-- Script to create the pie chart for the distribution of friends by gender -->
<script>
// Assigns the gender distribution data to JavaScript variables
const genders = {
    female: <?= $genders['F']; ?>,
    male: <?= $genders['M']; ?>,
    other: <?= $genders['O']; ?>
};

// Gets the context for the pie chart canvas
const ctx = document.getElementById('genderChart').getContext('2d');

// Creates the pie chart using Chart.js
const genderChart = new Chart(ctx, {
    type: 'pie', // Specifies that the chart is a pie chart
    data: {
        labels: ['Female', 'Male', 'Other'], // Labels for the gender categories
        datasets: [{
            label: 'Number of Registered Friends', // Label for the dataset
            data: [genders.female, genders.male, genders.other], // Data for each gender category
            backgroundColor: [
                'rgba(255, 0, 0, 0.8)', // Red color for female
                'rgba(0, 0, 255, 0.8)', // Blue color for male
                'rgba(0, 255, 0, 0.8)'  // Green color for other
            ],
            borderColor: [
                'rgba(255, 0, 0, 1)', // Red border for female
                'rgba(0, 0, 255, 1)', // Blue border for male
                'rgba(0, 255, 0, 1)'  // Green border for other
            ],
            borderWidth: 2 // Border width
        }]
    },
    options: {
        responsive: true, // Makes the chart responsive
        plugins: {
            legend: {
                position: 'top', // Position of the legend
            },
            title: {
                display: true, // Display the title
                text: 'Distribution of Friends by Gender' // Title of the chart
            }
        }
    }
});
</script>
