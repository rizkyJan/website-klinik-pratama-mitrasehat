@extends('layouts.app')



@section('pageTitle', 'Klinik Pratama Mitra Sehat')



@section('content')

<style>
    :root {

        --ms-cream: #fbf7e9;

        --ms-green: #086a34;

        --ms-green-dark: #07562c;

        --ms-soft: #d9efd6;

        --ms-soft-2: #eef5e4;

        --ms-yellow: #efbd3d;

        --ms-text: #5f685f;

        --ms-line: #e8eadf;

    }



    .ms-home,

    .ms-home * {

        box-sizing: border-box;

    }



    .ms-home {

        background: var(--ms-cream);

        font-family: 'Poppins', 'Trebuchet MS', Arial, sans-serif;

        color: var(--ms-text);

        overflow-x: clip;

    }



    .ms-wrap {

        width: min(1280px, calc(100% - 48px));

        margin-inline: auto;

    }



    .ms-carousel {

        position: relative;

    }



    .ms-slide {

        display: none;

        animation: msFade .55s ease both;

    }



    .ms-slide.is-active {

        display: block;

    }



    @keyframes msFade {

        from {

            opacity: 0;

            transform: translateY(8px);

        }



        to {

            opacity: 1;

            transform: translateY(0);

        }

    }



    /* =========================================================

       SLIDE 1 - HOME

    ========================================================= */

    .ms-slide-home {

        padding: 20px 0 28px;

    }



    .ms-hero {

        position: relative;

        min-height: 300px;

        display: flex;

        align-items: center;

        overflow: visible;

        background: var(--ms-soft);

        border-radius: 34px;

    }



    .ms-hero-copy {

        width: 61%;

        padding: 34px 42px 32px 46px;

        position: relative;

        z-index: 2;

    }



    .ms-hero-title {

        margin: 0;

        max-width: 520px;

        color: #036b17;

        font-size: clamp(38px, 3.35vw, 58px);

        line-height: 1.02;

        letter-spacing: -1px;

        font-weight: 800;

    }



    .ms-hero-tagline {

        margin: 14px 0 0;

        color: #2f7247;

        font-size: clamp(17px, 1.45vw, 23px);

        line-height: 1.2;

        font-weight: 700;

    }



    .ms-hero-desc {

        margin: 15px 0 0;

        max-width: 470px;

        color: #5a625b;

        font-size: 15px;

        line-height: 1.6;

    }



    .ms-actions {

        display: flex;

        flex-wrap: wrap;

        gap: 12px;

        margin-top: 18px;

    }



    .ms-btn {

        min-height: 46px;

        display: inline-flex;

        align-items: center;

        justify-content: center;

        padding: 0 20px;

        border-radius: 7px;

        font-size: 13.5px;

        font-weight: 700;

        line-height: 1;

        text-decoration: none;

        transition: transform .2s ease, background .2s ease;

        white-space: nowrap;

    }



    .ms-btn:hover {

        transform: translateY(-1px);

    }



    .ms-btn-primary {

        background: #006b00;

        color: #fff;

        border: 1px solid #006b00;

    }



    .ms-btn-outline {

        background: rgba(255, 255, 255, .78);

        color: #086a34;

        border: 1px solid #086a34;

    }



    .ms-clinic-photo {

        position: absolute;

        z-index: 3;

        right: 3.1%;

        top: -12px;

        width: 27.5%;

        height: calc(100% + 24px);

        overflow: hidden;

        border: 3px solid #087127;

        border-radius: 25px;

        background: #fff;

    }



    .ms-clinic-photo img {

        width: 100%;

        height: 100%;

        display: block;

        object-fit: cover;

        object-position: center center;

    }



    .ms-info-grid {

        display: grid;

        grid-template-columns: repeat(4, minmax(0, 1fr));

        gap: 24px;

        margin: 27px 18px 0;

    }



    .ms-info-card {

        min-height: 100px;

        padding: 14px 16px;

        border: 1px solid var(--ms-line);

        border-radius: 13px;

        background: #fff;

        display: flex;

        flex-direction: column;

        align-items: center;

        justify-content: center;

        text-align: center;

        text-decoration: none;

    }



    .ms-info-card strong {

        color: #31754b;

        font-size: 13px;

        line-height: 1.2;

        margin-bottom: 8px;

    }



    .ms-info-card span {

        color: #777f79;

        font-size: 11.5px;

        line-height: 1.5;

    }



    .ms-teaser {

        padding: 40px 24px 12px;

    }



    .ms-teaser h2,

    .ms-doctor-head h2 {

        margin: 0;

        color: #2f754b;

        font-weight: 800;

        letter-spacing: -.45px;

    }



    .ms-teaser h2 {

        font-size: clamp(24px, 2.05vw, 31px);

    }



    .ms-teaser p,

    .ms-doctor-head p {

        margin: 7px 0 0;

        color: #6e766f;

        font-size: 13.5px;

        line-height: 1.55;

    }



    .ms-more {

        margin-top: 21px;

        min-height: 42px;

        padding: 0 16px;

        display: inline-flex;

        align-items: center;

        gap: 7px;

        border-radius: 6px;

        background: #2f7a4e;

        color: #fff;

        text-decoration: none;

        font-size: 12.5px;

        font-weight: 700;

    }



    /* =========================================================

       SLIDE 2 - DOCTOR DESKTOP/TABLET

    ========================================================= */

    .ms-slide-doctor {

        padding: 25px 0 28px;

    }



    .ms-doctor-head {

        margin: 0 0 22px 8px;

    }



    .ms-doctor-head h2 {

        font-size: clamp(24px, 2.05vw, 31px);

    }



    .ms-doctor-banner {

        position: relative;

        width: 91%;

        height: 420px;

        margin: 0 auto;

        overflow: hidden;

        border-radius: 30px;

        background: linear-gradient(90deg, #eef4df 0%, #f5f7e9 52%, #eff3df 100%);

    }



    .ms-left-shape {

        position: absolute;

        inset: 0 auto 0 0;

        width: 39%;

        background:

            radial-gradient(circle at 13% 12%, #acd49f 0 18%, transparent 18.5%),

            radial-gradient(circle at 25% 75%, #98c88c 0 32%, transparent 32.5%),

            linear-gradient(110deg, #dcebd0 0%, #cde4c1 68%, transparent 68%);

        border-radius: 30px 80px 30px 30px;

    }



    .ms-doctor-person {

        position: absolute;

        z-index: 3;

        left: 12.8%;

        bottom: -2px;

        height: 100%;

        width: auto;

        object-fit: contain;

        object-position: bottom center;

    }



    .ms-handwriting {

        position: absolute;

        z-index: 4;

        left: 3.7%;

        top: 18%;

        width: 13%;

        color: #075f33;

        font-family: Georgia, 'Times New Roman', serif;

        font-size: clamp(15px, 1.45vw, 23px);

        font-weight: 700;

        font-style: italic;

        line-height: 1.05;

        text-align: center;

        transform: rotate(-4deg);

    }



    .ms-yellow-swoop {

        position: absolute;

        z-index: 4;

        left: 7.4%;

        top: 45%;

        width: 10%;

        height: 4px;

        border-radius: 999px;

        background: var(--ms-yellow);

        transform: rotate(-11deg);

    }



    .ms-name-card {

        position: absolute;

        z-index: 5;

        left: 3.2%;

        bottom: 72px;

        width: min(250px, 27%);

        min-width: 220px;

        padding: 11px 14px 10px;

        border-radius: 15px;

        background: #0b6c35;

        color: #fff;

        text-align: center;

        box-shadow: 0 4px 10px rgba(15, 82, 45, .08);

    }



    .ms-name-card b {

        display: block;

        font-size: 14px;

        line-height: 1.2;

        text-wrap: balance;

    }



    .ms-name-card small {

        display: block;

        margin-top: 4px;

        font-size: 9.5px;

        font-weight: 600;

    }



    .ms-heart-chip {

        position: absolute;

        z-index: 5;

        left: 7.1%;

        bottom: 28px;

        padding: 8px 13px;

        display: flex;

        align-items: center;

        gap: 7px;

        border-radius: 17px;

        background: #f3d878;

        color: #4f684e;

        font-size: 9px;

        line-height: 1.35;

    }



    .ms-heart-chip svg {

        flex: none;

        width: 17px;

        height: 17px;

        color: #0f7a45;

    }



    .ms-doctor-copy {

        position: absolute;

        z-index: 4;

        left: 42.5%;

        top: 34px;

        width: 31.5%;

        padding-right: 10px;

    }



    .ms-pill {

        min-height: 28px;

        padding: 0 11px;

        display: inline-flex;

        align-items: center;

        gap: 8px;

        border-radius: 14px;

        background: #dcefd0;

        color: #22673d;

        font-size: 10px;

        font-weight: 700;

        letter-spacing: .3px;

    }



    .ms-pill svg {

        width: 19px;

        height: 19px;

        flex: none;

    }



    .ms-doctor-copy h3 {

        position: relative;

        margin: 12px 0 0;

        max-width: 100%;

        color: #006b37;

        font-size: clamp(24px, 2.15vw, 34px);

        line-height: 1.12;

        font-weight: 700;

        letter-spacing: -.6px;

        white-space: normal;

        overflow-wrap: break-word;

        word-break: normal;

        text-wrap: balance;

    }



    .ms-doctor-copy h3::after {

        content: '';

        position: absolute;

        left: 2px;

        bottom: -8px;

        width: 96%;

        height: 4px;

        border-radius: 999px;

        background: var(--ms-yellow);

        transform: rotate(-1deg);

    }



    .ms-doctor-sub {

        margin-top: 16px;

        color: #75a263;

        font-size: clamp(14px, 1.25vw, 18px);

        line-height: 1.2;

        font-weight: 700;

    }



    .ms-doctor-text {

        margin-top: 14px;

        color: #53815b;

        font-size: 11.5px;

        line-height: 1.5;

    }



    .ms-feature-row {

        position: absolute;

        z-index: 5;

        left: 42.5%;

        bottom: 27px;

        width: 32.5%;

        display: grid;

        grid-template-columns: repeat(4, minmax(0, 1fr));

        gap: 8px;

    }



    .ms-feature {

        color: #115e34;

        font-size: 9.5px;

        line-height: 1.3;

        font-weight: 600;

        text-align: center;

    }



    .ms-feature-icon {

        width: 48px;

        height: 48px;

        margin: 0 auto 6px;

        display: flex;

        align-items: center;

        justify-content: center;

        border-radius: 50%;

        background: #dceccf;

        color: #08743c;

    }



    .ms-feature-icon svg {

        width: 29px;

        height: 29px;

        flex: none;

        stroke-width: 1.9;

    }



    /* Right-side doctor quote: matched to the Canva reference. */

    .ms-quote-panel {

        position: absolute;

        z-index: 6;

        right: 4.1%;

        top: 46px;

        width: 21%;

        color: #12653d;

        text-align: center;

    }



    .ms-quote-mark {

        position: absolute;

        z-index: 2;

        left: -12px;

        top: -39px;

        color: #cfe8bd;

        font-family: Georgia, 'Times New Roman', serif;

        font-size: 78px;

        font-weight: 700;

        line-height: 1;

    }



    .ms-quote-box {

        position: relative;

        min-height: 130px;

        padding: 22px 17px 18px;

        display: flex;

        align-items: center;

        justify-content: center;

        border-top: 3px solid #bdd9a5;

        border-right: 3px solid #bdd9a5;

        border-bottom: 3px solid #bdd9a5;

        border-left: 0;

        border-radius: 0 30px 30px 30px;

        background: transparent;

        color: #11633b;

        font-size: 14.5px;

        line-height: 1.18;

        font-weight: 500;

    }



    .ms-quote-name {

        margin-top: 14px;

        color: #08623a;

        font-size: 16px;

        font-weight: 700;

        line-height: 1.12;

    }



    .ms-quote-name span {

        display: block;

        margin-top: 3px;

        color: #4d7f60;

        font-size: 10px;

        font-weight: 500;

        line-height: 1.25;

    }



    .ms-doctor-btn {

        position: absolute;

        z-index: 5;

        right: 4.2%;

        bottom: 29px;

        min-width: 165px;

        min-height: 46px;

        padding: 0 18px;

        display: inline-flex;

        align-items: center;

        justify-content: center;

        gap: 16px;

        border-radius: 8px;

        background: #006b37;

        color: #fff;

        text-decoration: none;

        font-size: 12px;

        font-weight: 700;

    }



    .ms-stethoscope-line {

        position: absolute;

        z-index: 2;

        right: -8px;

        bottom: -28px;

        width: 126px;

        height: 146px;

        border-right: 5px solid #c9e4bb;

        border-bottom: 5px solid #c9e4bb;

        border-top: 0;

        border-left: 0;

        border-radius: 0 0 62px 0;

        transform: rotate(13deg);

        opacity: .72;

        pointer-events: none;

    }



    .ms-stethoscope-line::before {

        content: '';

        position: absolute;

        right: 26px;

        top: -25px;

        width: 51px;

        height: 54px;

        border-right: 5px solid #c9e4bb;

        border-bottom: 5px solid #c9e4bb;

        border-radius: 0 0 25px 0;

    }



    .ms-stethoscope-line::after {

        content: '';

        position: absolute;

        right: 50px;

        bottom: -4px;

        width: 13px;

        height: 13px;

        border: 4px solid #c9e4bb;

        border-radius: 50%;

        background: transparent;

    }



    /* Mobile doctor layout intentionally separate so it remains readable. */

    .ms-doctor-mobile {

        display: none;

    }



    .ms-dot-nav {

        display: flex;

        justify-content: center;

        gap: 7px;

        margin-top: 12px;

    }



    .ms-dot {

        width: 7px;

        height: 7px;

        border: 0;

        border-radius: 50%;

        background: #c6d8c9;

        cursor: pointer;

        padding: 0;

    }



    .ms-dot.is-active {

        width: 20px;

        border-radius: 999px;

        background: #2f7a4e;

    }



    /* =========================================================

       TABLET

    ========================================================= */

    @media (max-width: 1024px) {

        .ms-wrap {

            width: min(100% - 36px, 900px);

        }



        .ms-hero {

            min-height: 320px;

        }



        .ms-hero-copy {

            width: 59%;

            padding-left: 30px;

        }



        .ms-clinic-photo {

            width: 35%;

            right: 2.5%;

        }



        .ms-info-grid {

            gap: 14px;

            margin-inline: 0;

        }



        .ms-doctor-banner {

            width: 100%;

            height: 420px;

        }



        .ms-doctor-person {

            left: 7%;

        }



        .ms-handwriting,

        .ms-yellow-swoop {

            display: none;

        }



        .ms-name-card {

            left: 2.5%;

        }



        .ms-heart-chip {

            left: 4%;

        }



        .ms-doctor-copy {

            left: 39%;

            width: 35%;

            top: 32px;

        }



        .ms-doctor-copy h3 {

            font-size: clamp(23px, 2.6vw, 30px);

            line-height: 1.12;

        }



        .ms-feature-row {

            left: 39%;

            width: 36%;

        }



        .ms-quote-panel {

            right: 2.5%;

            width: 21%;

        }



        .ms-doctor-btn {

            right: 2.5%;

        }

    }



    /* =========================================================

       MOBILE

    ========================================================= */

    @media (max-width: 767px) {

        .ms-home {

            overflow-x: hidden;

        }



        .ms-wrap {

            width: calc(100% - 24px);

        }



        .ms-slide-home,

        .ms-slide-doctor {

            padding: 12px 0 22px;

        }



        .ms-hero {

            min-height: 0;

            display: block;

            padding: 0 0 14px;

            border-radius: 22px;

            overflow: hidden;

        }



        .ms-hero-copy {

            width: 100%;

            padding: 23px 20px 16px;

        }



        .ms-hero-title {

            max-width: none;

            font-size: clamp(30px, 10.2vw, 40px);

            line-height: 1.02;

            letter-spacing: -.6px;

        }



        .ms-hero-tagline {

            margin-top: 11px;

            font-size: 15px;

        }



        .ms-hero-desc {

            margin-top: 13px;

            max-width: none;

            font-size: 13px;

            line-height: 1.6;

        }



        .ms-actions {

            display: grid;

            grid-template-columns: 1fr 1fr;

            gap: 9px;

            margin-top: 16px;

        }



        .ms-btn {

            width: 100%;

            min-height: 42px;

            padding: 0 11px;

            font-size: 11.5px;

        }



        .ms-clinic-photo {

            position: relative;

            top: auto;

            right: auto;

            width: calc(100% - 28px);

            height: auto;

            aspect-ratio: 16 / 9;

            margin: 3px 14px 0;

            border-width: 2px;

            border-radius: 18px;

        }



        .ms-info-grid {

            grid-template-columns: repeat(2, minmax(0, 1fr));

            gap: 9px;

            margin: 12px 0 0;

        }



        .ms-info-card {

            min-height: 88px;

            padding: 11px 9px;

            border-radius: 11px;

        }



        .ms-info-card strong {

            margin-bottom: 5px;

            font-size: 10.5px;

        }



        .ms-info-card span {

            font-size: 9px;

            line-height: 1.4;

        }



        .ms-teaser {

            padding: 26px 2px 4px;

        }



        .ms-teaser h2,

        .ms-doctor-head h2 {

            font-size: 22px;

            line-height: 1.25;

        }



        .ms-teaser p,

        .ms-doctor-head p {

            font-size: 12px;

        }



        .ms-more {

            margin-top: 16px;

            min-height: 40px;

            font-size: 11.5px;

        }



        .ms-doctor-head {

            margin: 0 0 16px;

        }



        .ms-doctor-banner {

            display: none;

        }



        .ms-doctor-mobile {

            display: block;

            overflow: hidden;

            border-radius: 22px;

            background: linear-gradient(180deg, #eef4df 0%, #f8faef 100%);

        }



        .ms-mobile-photo-zone {

            position: relative;

            min-height: 320px;

            overflow: hidden;

            background:

                radial-gradient(circle at 12% 12%, #acd49f 0 17%, transparent 17.5%),

                radial-gradient(circle at 24% 78%, #98c88c 0 27%, transparent 27.5%),

                linear-gradient(120deg, #dcebd0 0%, #cde4c1 70%, #edf3df 70%);

        }



        .ms-mobile-handwriting {

            position: absolute;

            left: 16px;

            top: 28px;

            z-index: 3;

            width: 82px;

            color: #075f33;

            font-family: Georgia, 'Times New Roman', serif;

            font-size: 17px;

            line-height: 1.03;

            font-style: italic;

            font-weight: 700;

            text-align: center;

            transform: rotate(-5deg);

        }



        .ms-mobile-doctor {

            position: absolute;

            left: 50%;

            bottom: -1px;

            z-index: 2;

            height: 310px;

            width: auto;

            max-width: 76%;

            transform: translateX(-42%);

            object-fit: contain;

            object-position: bottom center;

        }



        .ms-mobile-name {

            position: absolute;

            z-index: 4;

            left: 50%;

            bottom: 15px;

            min-width: 166px;

            padding: 9px 15px;

            border-radius: 14px;

            background: #0b6c35;

            color: #fff;

            text-align: center;

            transform: translateX(-50%);

        }



        .ms-mobile-name b {

            display: block;

            font-size: 15px;

        }



        .ms-mobile-name small {

            display: block;

            margin-top: 3px;

            font-size: 8.5px;

        }



        .ms-mobile-copy {

            padding: 22px 18px 20px;

            text-align: left;

        }



        .ms-mobile-copy .ms-pill {

            min-height: 29px;

            font-size: 10px;

        }



        .ms-mobile-copy h3 {

            position: relative;

            margin: 13px 0 0;

            padding-bottom: 10px;

            color: #006b37;

            font-size: 27px;

            line-height: 1.08;

            font-weight: 700;

            letter-spacing: -.5px;

        }



        .ms-mobile-copy h3::after {

            content: '';

            position: absolute;

            left: 0;

            bottom: 1px;

            width: 82%;

            height: 3px;

            border-radius: 999px;

            background: var(--ms-yellow);

        }



        .ms-mobile-sub {

            margin-top: 8px;

            color: #75a263;

            font-size: 14px;

            font-weight: 700;

            line-height: 1.25;

        }



        .ms-mobile-text {

            margin-top: 13px;

            color: #53815b;

            font-size: 11.5px;

            line-height: 1.6;

        }



        .ms-mobile-quote {

            position: relative;

            margin-top: 16px;

            padding: 16px 15px;

            border: 2px solid #c4dea8;

            border-radius: 16px;

            color: #16673b;

            background: rgba(255, 255, 255, .45);

            font-size: 11.5px;

            font-weight: 600;

            line-height: 1.4;

            text-align: center;

        }



        .ms-mobile-features {

            display: grid;

            grid-template-columns: repeat(2, minmax(0, 1fr));

            gap: 14px 10px;

            margin-top: 18px;

        }



        .ms-mobile-features .ms-feature {

            font-size: 9.5px;

        }



        .ms-mobile-features .ms-feature-icon {

            width: 50px;

            height: 50px;

            margin-bottom: 7px;

        }



        .ms-mobile-features .ms-feature-icon svg {

            width: 30px;

            height: 30px;

        }



        .ms-mobile-about {

            width: 100%;

            min-height: 43px;

            margin-top: 18px;

            padding: 0 16px;

            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 10px;

            border-radius: 8px;

            background: #006b37;

            color: #fff;

            text-decoration: none;

            font-size: 11.5px;

            font-weight: 700;

        }



        .ms-dot-nav {

            margin-top: 10px;

        }

    }



    @media (max-width: 360px) {

        .ms-wrap {

            width: calc(100% - 18px);

        }



        .ms-hero-copy {

            padding: 20px 16px 14px;

        }



        .ms-hero-title {

            font-size: 30px;

        }



        .ms-actions {

            grid-template-columns: 1fr;

        }



        .ms-clinic-photo {

            width: calc(100% - 22px);

            margin-inline: 11px;

        }



        .ms-mobile-photo-zone {

            min-height: 295px;

        }



        .ms-mobile-doctor {

            height: 285px;

        }



        .ms-mobile-copy {

            padding-inline: 15px;

        }

    }



    @media (prefers-reduced-motion: reduce) {

        .ms-slide {

            animation: none;

        }

    }
</style>



<div class="ms-home" id="msHomeCarousel">

    <div class="ms-carousel" aria-live="polite">



        {{-- ============================================================

             SLIDE 1: HOME

        ============================================================= --}}

        <section class="ms-slide ms-slide-home is-active" data-ms-slide="0">

            <div class="ms-wrap">

                <div class="ms-hero">

                    <div class="ms-hero-copy">

                        <h1 class="ms-hero-title">{{ $clinicName }}</h1>

                        <p class="ms-hero-tagline">{{ $clinicTagline }}</p>

                        <p class="ms-hero-desc">

                            Pelayanan kesehatan yang ramah, profesional,

                            dan mudah diakses untuk masyarakat.

                        </p>



                        <div class="ms-actions">

                            <a href="{{ route('registration') }}" class="ms-btn ms-btn-primary">Info Pendaftaran</a>

                            <a href="{{ route('services') }}" class="ms-btn ms-btn-outline">Lihat Layanan</a>

                        </div>

                    </div>



                    <div class="ms-clinic-photo">

                        <img src="{{ asset('images/foto-klinik.png') }}" alt="Gedung Klinik Pratama Mitra Sehat">

                    </div>

                </div>



                <div class="ms-info-grid">

                    <div class="ms-info-card">

                        <strong>Jam Pelayanan</strong>

                        <span>Senin s.d Sabtu (24 jam)<br>Minggu (07.00 - 21.00 WIB)</span>

                    </div>



                    <div class="ms-info-card">

                        <strong>Lokasi</strong>

                        <span>Jl. Veteran No. 70<br>Sukoharjo</span>

                    </div>



                    <div class="ms-info-card">

                        <strong>BPJS / JKN</strong>

                        <span>Pelayanan sesuai ketentuan</span>

                    </div>



                    <a href="{{ route('registration') }}" class="ms-info-card">

                        <strong>Pendaftaran</strong>

                        <span>Datang langsung atau melalui Mobile JKN</span>

                    </a>

                </div>



                @include('partials.home-announcements', ['announcements' => $announcements])



                <div class="ms-teaser">

                    <h2>Kenal Lebih Dekat dengan Mitra Sehat</h2>

                    <p>Berbagai layanan kesehatan untuk mendukung kebutuhan Anda dan keluarga.</p>

                    <a href="{{ route('about') }}" class="ms-more">Selengkapnya <span>→</span></a>

                </div>

            </div>

        </section>



        {{-- ============================================================

             SLIDE 2: SAMBUTAN DOKTER

        ============================================================= --}}

        <section class="ms-slide ms-slide-doctor" data-ms-slide="1">

            <div class="ms-wrap">

                <div class="ms-doctor-head">

                    <h2>Kenal Lebih Dekat dengan Mitra Sehat</h2>

                    <p>Berbagai layanan kesehatan untuk mendukung kebutuhan Anda dan keluarga.</p>

                </div>



                {{-- Desktop/tablet banner --}}

                <div class="ms-doctor-banner">

                    <div class="ms-left-shape"></div>



                    <div class="ms-handwriting">“Sehat<br>Bersama,<br>Lebih Baik<br>Setiap Hari”</div>

                    <div class="ms-yellow-swoop"></div>



                    <img

                        src="{{ asset('images/foto-bu-aul-baru.png') }}"

                        alt="dr.Auliya Andriyati, Sp.PD FINASIM - Klinik Pratama Mitra Sehat"

                        class="ms-doctor-person">



                    <div class="ms-name-card">

                        <b>dr.Auliya Andriyati, Sp.PD FINASIM</b>

                        <small>Klinik Pratama Mitra Sehat</small>

                    </div>



                    <div class="ms-heart-chip">

                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 000-7.78z" />

                        </svg>

                        <span>Mitra Tepat Menuju Sehat</span>

                    </div>



                    <div class="ms-doctor-copy">

                        <div class="ms-pill">

                            SAPA DOKTER

                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 3v5a4 4 0 008 0V3M4 3h4M12 3h4M10 13v2a5 5 0 0010 0v-1.2" />

                                <circle cx="20" cy="11" r="2" stroke-width="2" />

                            </svg>

                        </div>



                        <h3>Halo, Saya dr.Auliya Andriyati, Sp.PD FINASIM</h3>

                        <div class="ms-doctor-sub">Bersama Anda, untuk Hidup Lebih Sehat</div>



                        <div class="ms-doctor-text">

                            Selamat datang di Klinik Pratama Mitra Sehat.<br>

                            Kami hadir dengan komitmen memberikan pelayanan kesehatan yang ramah,

                            profesional, dan mudah diakses untuk Anda dan keluarga.

                            <br><br>

                            Semoga Klinik Pratama Mitra Sehat dapat terus menjadi mitra terpercaya

                            dalam menjaga kesehatan masyarakat.

                        </div>

                    </div>



                    <div class="ms-feature-row">

                        <div class="ms-feature">

                            <div class="ms-feature-icon">

                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">

                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2M9 11a4 4 0 100-8 4 4 0 000 8zM22 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />

                                </svg>

                            </div>

                            Pelayanan<br>Ramah

                        </div>



                        <div class="ms-feature">

                            <div class="ms-feature-icon">

                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">

                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />

                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4" />

                                </svg>

                            </div>

                            Profesional dan<br>Terpercaya

                        </div>



                        <div class="ms-feature">

                            <div class="ms-feature-icon">

                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">

                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 000-7.78z" />

                                </svg>

                            </div>

                            Mudah<br>Diakses

                        </div>



                        <div class="ms-feature">

                            <div class="ms-feature-icon">

                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">

                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 11l9-8 9 8M5 10v10h5v-6h4v6h5V10" />

                                </svg>

                            </div>

                            Untuk Anda<br>dan Keluarga

                        </div>

                    </div>



                    <div class="ms-quote-panel">

                        <div class="ms-quote-mark">“</div>



                        <div class="ms-quote-box">

                            Kesehatan bukan hanya tujuan, tapi investasi untuk masa depan yang lebih baik.

                        </div>



                        <div class="ms-quote-name">

                            dr.Auliya Andriyati, Sp.PD FINASIM

                            <span>Klinik Pratama Mitra Sehat</span>

                        </div>

                    </div>



                    <a href="{{ route('about') }}" class="ms-doctor-btn">

                        Kenal Lebih Dekat <span>→</span>

                    </a>



                    <div class="ms-stethoscope-line" aria-hidden="true"></div>

                </div>



                {{-- Mobile doctor banner --}}

                <div class="ms-doctor-mobile">

                    <div class="ms-mobile-photo-zone">

                        <div class="ms-mobile-handwriting">“Sehat<br>Bersama,<br>Lebih Baik<br>Setiap Hari”</div>



                        <img

                            src="{{ asset('images/foto-bu-aul-baru.png') }}"

                            alt="dr.Auliya Andriyati, Sp.PD FINASIM - Klinik Pratama Mitra Sehat"

                            class="ms-mobile-doctor">



                        <div class="ms-mobile-name">

                            <b>dr.Auliya Andriyati, Sp.PD FINASIM</b>

                            <small>Klinik Pratama Mitra Sehat</small>

                        </div>

                    </div>



                    <div class="ms-mobile-copy">

                        <div class="ms-pill">

                            SAPA DOKTER

                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 3v5a4 4 0 008 0V3M4 3h4M12 3h4M10 13v2a5 5 0 0010 0v-1.2" />

                                <circle cx="20" cy="11" r="2" stroke-width="2" />

                            </svg>

                        </div>



                        <h3>Halo, Saya dr.Auliya Andriyati, Sp.PD FINASIM</h3>

                        <div class="ms-mobile-sub">Bersama Anda, untuk Hidup Lebih Sehat</div>



                        <div class="ms-mobile-text">

                            Selamat datang di Klinik Pratama Mitra Sehat. Kami hadir dengan komitmen

                            memberikan pelayanan kesehatan yang ramah, profesional, dan mudah diakses

                            untuk Anda dan keluarga.

                        </div>



                        <div class="ms-mobile-quote">

                            “Kesehatan bukan hanya tujuan, tapi investasi untuk masa depan yang lebih baik.”

                        </div>



                        <div class="ms-mobile-features">

                            <div class="ms-feature">

                                <div class="ms-feature-icon">

                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">

                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2M9 11a4 4 0 100-8 4 4 0 000 8zM22 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />

                                    </svg>

                                </div>

                                Pelayanan<br>Ramah

                            </div>



                            <div class="ms-feature">

                                <div class="ms-feature-icon">

                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">

                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />

                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4" />

                                    </svg>

                                </div>

                                Profesional dan<br>Terpercaya

                            </div>



                            <div class="ms-feature">

                                <div class="ms-feature-icon">

                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">

                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 000-7.78z" />

                                    </svg>

                                </div>

                                Mudah<br>Diakses

                            </div>



                            <div class="ms-feature">

                                <div class="ms-feature-icon">

                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">

                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 11l9-8 9 8M5 10v10h5v-6h4v6h5V10" />

                                    </svg>

                                </div>

                                Untuk Anda<br>dan Keluarga

                            </div>

                        </div>



                        <a href="{{ route('about') }}" class="ms-mobile-about">

                            Kenal Lebih Dekat <span>→</span>

                        </a>

                    </div>

                </div>

            </div>

        </section>



        <div class="ms-dot-nav" aria-label="Navigasi banner">

            <button class="ms-dot is-active" type="button" data-ms-dot="0" aria-label="Tampilkan informasi klinik"></button>

            <button class="ms-dot" type="button" data-ms-dot="1" aria-label="Tampilkan sambutan dokter"></button>

        </div>

    </div>

</div>



<script>
    document.addEventListener('DOMContentLoaded', function() {

        const root = document.getElementById('msHomeCarousel');

        if (!root) return;



        const slides = Array.from(root.querySelectorAll('[data-ms-slide]'));

        const dots = Array.from(root.querySelectorAll('[data-ms-dot]'));

        if (slides.length < 2) return;



        let active = 0;

        let timer = null;

        const interval = 8000;



        function show(index) {

            active = (index + slides.length) % slides.length;



            slides.forEach((slide, i) => {

                slide.classList.toggle('is-active', i === active);

            });



            dots.forEach((dot, i) => {

                dot.classList.toggle('is-active', i === active);

            });

        }



        function next() {

            show(active + 1);

        }



        function start() {

            stop();

            timer = window.setInterval(next, interval);

        }



        function stop() {

            if (timer) {

                window.clearInterval(timer);

                timer = null;

            }

        }



        dots.forEach((dot, index) => {

            dot.addEventListener('click', function() {

                show(index);

                start();

            });

        });



        root.addEventListener('mouseenter', stop);

        root.addEventListener('mouseleave', start);



        let touchStartX = null;



        root.addEventListener('touchstart', function(event) {

            touchStartX = event.changedTouches[0].clientX;

            stop();

        }, {

            passive: true

        });



        root.addEventListener('touchend', function(event) {

            if (touchStartX === null) return;



            const delta = event.changedTouches[0].clientX - touchStartX;



            if (Math.abs(delta) > 45) {

                show(active + (delta < 0 ? 1 : -1));

            }



            touchStartX = null;

            start();

        }, {

            passive: true

        });



        start();

    });
</script>

@endsection