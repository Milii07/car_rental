<?php
function showFutureBlockBackground() {
  echo '
    <style>
      body {
        position: relative;
        background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
        color: #333;
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      }

      .futureblock-top,
      .futureblock-bottom {
        position: fixed;
        left: 50%;
        transform: translateX(-50%);
        font-size: 6rem;
        font-weight: 900;
        color: rgba(255, 255, 255, 0.15);
        letter-spacing: 10px;
        user-select: none;
        pointer-events: none;
        z-index: 0;
        white-space: nowrap;
      }

      .futureblock-top { top: 20px; }
      .futureblock-bottom { bottom: 20px; }

      body::before {
        content: "Auto";
        position: fixed;
        top: 50%;
        left: 5%;
        transform: translateY(-50%);
        font-size: 6rem;
        font-weight: 900;
        color: rgba(255, 255, 255, 0.12);
        letter-spacing: 12px;
        user-select: none;
        pointer-events: none;
        z-index: 0;
        white-space: nowrap;
      }

      body::after {
        content: "Block";
        position: fixed;
        top: 50%;
        right: 5%;
        transform: translateY(-50%);
        font-size: 6rem;
        font-weight: 900;
        color: rgba(255, 255, 255, 0.12);
        letter-spacing: 12px;
        user-select: none;
        pointer-events: none;
        z-index: 0;
        white-space: nowrap;
      }

      .futureblock-middle {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 6rem;
        font-weight: 900;
        color: rgba(255, 255, 255, 0.12);
        letter-spacing: 12px;
        user-select: none;
        pointer-events: none;
        z-index: 0;
        white-space: nowrap;
      }
    </style>

    <div class="futureblock-top">Auto Future Block</div>
    <div class="futureblock-bottom">Auto Future Block</div>
    <div class="futureblock-middle">Future</div>
  ';
}
?>
