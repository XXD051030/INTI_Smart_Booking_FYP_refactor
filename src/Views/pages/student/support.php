<?php declare(strict_types=1); ?>
<style>
    .profile-card {
        background: #ecf0f5;
        border-radius: 51px;
        padding: 40px;
        margin-bottom: 20px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        text-align: left;
        font-family: 'Roboto', sans-serif;
    }
    .profile-card h3 {
        font-size: 1.8rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 1rem;
        border-bottom: 2px solid #d0d4d9;
        padding-bottom: 0.5rem;
    }
    .profile-card p {
        font-size: 1.05rem;
        color: #444;
        line-height: 1.6;
        margin-bottom: 0.5rem;
    }
    .profile-card a {
        color: #0069d9;
        text-decoration: none;
        font-weight: 500;
    }
    .profile-card a:hover {
        text-decoration: underline;
    }
    .profile-card .btn {
        font-size: 1rem;
        font-weight: 500;
        padding: 0.5rem 1.25rem;
        border-radius: 10px;
        color: white;
    }
</style>
<h1 class="mb-4"><?= e(__('Support Page')) ?></h1>
<div class="profile-card">
    <div class="mb-5">
        <h3><?= e(__('Contact Information')) ?></h3>
        <p><?= e(__('Website')) ?> <a href="https://www.newinti.edu.my" target="_blank">https://www.newinti.edu.my</a></p>
        <p><?= e(__('email')) ?> iicp.adco@newinti.edu.my</p>
        <p><?= e(__('Phone')) ?> +04-631 0138</p>
        <p><?= e(__('Address')) ?> 1-Z Lebuh Bukit Jambul 11900 Penang, Malaysia</p>
        <a class="btn btn-success mt-3" href="mailto:iicp.adco@inti.edu.my?subject=Support%20Request&body=Please%20describe%20your%20issue%20here."><?= e(__('Click here to Email Support')) ?></a>
    </div>
</div>
