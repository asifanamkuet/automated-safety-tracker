# BIM-based Smart Safety Monitoring System

This repository contains the full code and documentation for a **BIM-based smart safety monitoring system** developed to enhance worker safety at construction sites. The system integrates a **mobile app** that tracks worker locations using GPS and a **web app** that visualizes safety data and issues alerts through a PHP-based API. The solution aims to mitigate accidents by identifying hazardous zones and notifying workers in real time.
## DOI for the original research paper: 10.1108/CI-11-2022-0296.
## Overview

The **BIM-based Smart Safety Monitoring System** provides a cloud-based, automated solution for safety tracking on construction sites. The system:
- Uses **Building Information Modeling (BIM)** to visualize the construction site in a 3D model.
- Leverages **GPS** technology for real-time tracking of worker movements.
- Utilizes a **mobile app** to track workers' locations and a **web app** for monitoring safety in real time.
- **PHP-based API** facilitates communication between the mobile and web apps.

## Features

- **Real-Time Worker Tracking**: Continuously tracks worker locations using GPS and updates in real time.
- **Hazard Zone Detection**: Alerts workers when they enter a designated hazardous area.
- **Safety Alerts**: Sends immediate safety alerts via SMS and push notifications to workers and managers when workers approach hazardous zones.
- **BIM Integration**: Visualizes tracked data on a BIM model of the construction site.
- **Web Interface**: Provides a dashboard for safety managers to monitor and manage site safety.
- **Historical Tracking**: Logs the movements of workers to provide insights and reports on their activity.

## Installation

### Prerequisites

To run this system, ensure the following tools and technologies are installed on your machine:

- **PHP 7.4+** with extensions:
  - `curl`
  - `mbstring`
  - `json`
  - `pdo_mysql`
- **MySQL Database**
- **Android Studio** for building and running the mobile app (API 29+)
- **Web Server** (Apache or Nginx)
