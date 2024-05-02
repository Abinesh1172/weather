<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Weather App</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
  body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-color: #f0f0f0;
    transition: background-color 0.5s;
    overflow: hidden;
    position: relative;
  }
  nav {
    width: 100%;
    background-color: #333;
    color: #fff;
    padding: 10px 20px;
    box-sizing: border-box;
    text-align: center;
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1000;
  }
  nav a {
    color: #fff;
    text-decoration: none;
    margin: 0 10px;
  }
  #weather {
    text-align: center;
    margin-top: 50px; /* Adjust margin to account for the fixed navbar */
  }
  .weather-icon {
    font-size: 3em;
    margin-bottom: 10px;
  }
  .night-theme {
    background-color: #333;
    color: #fff;
  }
  .rain {
    position: absolute;
    width: 100%;
    height: 100%;
    pointer-events: none;
    /* background-image: url('https://giphy.com/gifs/rain-raindrops-regentropfen-PgyRZjPX56s4868BKS'); */
    animation: rain 0.5s linear infinite;
  }
  .custom-rain {
    background-image: url('https://media0.giphy.com/media/v1.Y2lkPTc5MGI3NjExZGtreW1iZW9oODQ3b2x3OGNndHRqM293OXJjOXc2NXFvNGEycmJ1NiZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/mmv4ATlqgLC81TvAyg/giphy.gif');
    animation: customRain 0.5s linear infinite;
  }
  .dark-rain{
    background-image: url('https://media3.giphy.com/media/v1.Y2lkPTc5MGI3NjExNjJrNmlicWZxaGl1czdnanVmNXRlMmo0OXV2NzZhM3UyY3M0ZWlxbCZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/PgyRZjPX56s4868BKS/giphy.gif');
    animation: customRain 0.5s linear infinite;
  }
  @keyframes rain {
    0% {
      background-position: 0px 0px;
    }
    100% {
      background-position: 0px 100vh;
    }
  }
  @keyframes customRain {
    0% {
      background-position: 0px 0px;
    }
    100% {
      background-position: 0px 100vh;
    }
  }

  /* dropdown */
/* Dropdown container */
.dropdown {
  position: relative;
  display: inline-block;
}

/* Dropdown button */
.dropbtn {
  background-color: #333;
  color: white;
  padding: 10px 20px;
  font-size: 16px;
  border: none;
  cursor: pointer;
}

/* Dropdown content (hidden by default) */
.dropdown-content {
  display: none;
  position: absolute;
  background-color: #f9f9f9;
  min-width: 160px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
}

/* Links inside the dropdown */
.dropdown-content a {
  color: black;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
}

/* Change color of dropdown links on hover */
.dropdown-content a:hover {
  background-color: #f1f1f1;
}

/* Show the dropdown menu on hover */
.dropdown:hover .dropdown-content {
  display: block;
}

/* Change the background color of the dropdown button when the dropdown content is shown */
.dropdown:hover .dropbtn {
  background-color: #555;
}

/* search bar */
/* Search container */
 .search-container {
    position: relative;
    display: inline-block;
  }

  #searchDropdown {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
  }

  #searchDropdown a {
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
  }

  #searchDropdown a:hover {
    background-color: #f1f1f1;
  }

</style>
</head>
<body>
<nav>
    <a href="#">Home</a>
    <div class="dropdown">
      <a href="#" class="dropbtn">Trigger <i class="fa fa-caret-down"></i></a>
      <div class="dropdown-content">
        <a id="manual-rain-button">Rain</a>
      </div>
    </div>

    <div class="search-container">
      <input type="text" placeholder="Search city..." id="searchInput">
      <button type="button" onclick="searchCity()"><i class="fa fa-search"></i></button>
      <div class="dropdown-content" id="searchDropdown"></div>
    </div>

    <button onclick="toggleTheme()">Theme</button>
  </nav>
  <div id="weather"></div>
  <script>
    document.getElementById('manual-rain-button').addEventListener('click', () => {
      const isNightTheme = document.body.classList.contains('night-theme');
      if (isNightTheme) {
        document.body.classList.toggle('dark-rain');
        setTimeout(() => {
          document.body.classList.remove('dark-rain');
        }, 5000);
      } else {
        document.body.classList.toggle('custom-rain');
        setTimeout(() => {
          document.body.classList.remove('custom-rain');
        }, 5000);
      }
    });

    function toggleTheme() {
      const isDay = document.body.classList.contains('night-theme');
      document.body.classList.toggle('night-theme', !isDay);
    }

    function getWeather(latitude, longitude) {
      const apiKey = 'c3773f2dc2a3d24079f1e595c7cb6fa5';
      const weatherApiUrl = `https://api.openweathermap.org/data/2.5/weather?lat=${latitude}&lon=${longitude}&appid=${apiKey}&units=metric`;
      const reverseGeocodingApiUrl = `https://api.openweathermap.org/geo/1.0/reverse?lat=${latitude}&lon=${longitude}&limit=1&appid=${apiKey}`;

      fetch(reverseGeocodingApiUrl)
        .then(response => response.json())
        .then(reverseGeocodingData => {
          const cityName = reverseGeocodingData[0].name;
          const countryCode = reverseGeocodingData[0].country;
          fetch(`https://restcountries.com/v2/alpha/${countryCode}`)
            .then(response => response.json())
            .then(countryData => {
              const countryName = countryData.name;
              fetch(weatherApiUrl)
                .then(response => response.json())
                .then(weatherData => {
                  const weatherDescription = weatherData.weather[0].description;
                  const temperature = weatherData.main.temp;
                  const iconCode = weatherData.weather[0].icon;
                  const iconUrl = `http://openweathermap.org/img/w/${iconCode}.png`;

                  const isDay = weatherData.sys.sunrise < weatherData.dt && weatherData.dt < weatherData.sys.sunset;

                  document.getElementById('weather').innerHTML = `
                    <h2>${cityName}, ${countryName}</h2>
                    <img src="${iconUrl}" alt="Weather Icon" class="weather-icon">
                    <p>${weatherDescription}</p>
                    <p>Temperature: ${temperature}°C</p>
                  `;

                  document.body.classList.toggle('night-theme', !isDay);

                  if (weatherDescription.includes('rain')) {
                    document.body.classList.add('rain');
                  } else {
                    document.body.classList.remove('rain');
                  }
                })
                .catch(error => {
                  console.error('Error fetching weather data:', error);
                  document.getElementById('weather').innerHTML = 'Failed to fetch weather data';
                });
            })
            .catch(error => {
              console.error('Error fetching country data:', error);
              document.getElementById('weather').innerHTML = 'Failed to fetch country data';
            });
        })
        .catch(error => {
          console.error('Error getting location:', error);
          document.getElementById('weather').innerHTML = 'Failed to get location';
        });
    }

    function searchCity() {
      const searchInput = document.getElementById('searchInput').value;
      const apiKey = 'c3773f2dc2a3d24079f1e595c7cb6fa5';
      const searchApiUrl = `https://api.openweathermap.org/data/2.5/weather?q=${searchInput}&appid=${apiKey}&units=metric`;

      fetch(searchApiUrl)
        .then(response => response.json())
        .then(data => {
          const latitude = data.coord.lat;
          const longitude = data.coord.lon;
          getWeather(latitude, longitude);
        })
        .catch(error => {
          console.error('Error searching for city:', error);
          document.getElementById('weather').innerHTML = 'Failed to search for city';
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(position => {
          const { latitude, longitude } = position.coords;
          getWeather(latitude, longitude);
        }, error => {
          console.error('Error getting location:', error);
          document.getElementById('weather').innerHTML = 'Failed to get location';
        });
      } else {
        document.getElementById('weather').innerHTML = 'Geolocation is not supported by this browser';
      }
    });
  </script>
</body>
</html>