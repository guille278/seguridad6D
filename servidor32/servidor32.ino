#include "esp_camera.h"
#include <WiFi.h>
#include <DHT.h>
#include "HTTPClient.h"
#include "base64.h"
#include "ESP32_FTPClient.h"
#include <NTPClient.h>
#include <WiFiUdp.h>

const char *ssid = "xxx";
const char *password = "***";

String fileName = "";

WiFiUDP ntpUDP;
NTPClient timeClient(ntpUDP, "mx.pool.ntp.org", -18000);

String host = "https://0.0.0.0/API/api.php";
String auth = base64::encode("admin:12345");

char ftp_server[] = "files.000webhost.com";
char ftp_user[] = "xxx";
char ftp_pass[] = "***";

ESP32_FTPClient ftp(ftp_server, ftp_user, ftp_pass, 5000, 2);

DHT dht(12, DHT11);

#define PWDN_GPIO_NUM 32
#define RESET_GPIO_NUM -1
#define XCLK_GPIO_NUM 0
#define SIOD_GPIO_NUM 26
#define SIOC_GPIO_NUM 27

#define Y9_GPIO_NUM 35
#define Y8_GPIO_NUM 34
#define Y7_GPIO_NUM 39
#define Y6_GPIO_NUM 36
#define Y5_GPIO_NUM 21
#define Y4_GPIO_NUM 19
#define Y3_GPIO_NUM 18
#define Y2_GPIO_NUM 5
#define VSYNC_GPIO_NUM 25
#define HREF_GPIO_NUM 23
#define PCLK_GPIO_NUM 22

camera_config_t config;

void setup()
{
  pinMode(15, OUTPUT);
  pinMode(2, OUTPUT);
  pinMode(13, INPUT);

  Serial.begin(115200);
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED)
  {
    delay(500);
    Serial.print(".");
  }

  Serial.println("");
  Serial.println("WiFi connected.");
  Serial.println("IP address: ");
  Serial.println(WiFi.localIP());

  timeClient.begin();

  initCamera();

  dht.begin();
}

void loop()
{
  if (WiFi.status() == WL_CONNECTED)
  {
    digitalWrite(2, HIGH);
    if (digitalRead(13) == HIGH)
    {
      digitalWrite(15, HIGH);
      FTP_upload();
      enviaAlerta("1", "1");
      delay(5000);
    }
    else
    {
      digitalWrite(15, LOW);
    }
    if (dht.readHumidity() > 50)
    {
      enviaAlerta("2", "1");
      delay(20000);
    }
  }
  else
  {
    digitalWrite(2, LOW);
  }
}

void FTP_upload()
{
  Serial.println("Uploading via FTP");

  camera_fb_t *fb = NULL;
  // Take Picture with Camera
  fb = esp_camera_fb_get();
  if (!fb)
  {
    Serial.println("Camera capture failed");
    return;
  }
  ftp.OpenConnection();
  
  timeClient.update();
  fileName = timeClient.getFormattedDate() + ".jpg";
  int str_len = fileName.length() + 1;

  char char_array[str_len];
  fileName.toCharArray(char_array, str_len);

  ftp.InitFile("Type I");
  ftp.ChangeWorkDir("public_html/captures/");
  ftp.NewFile(char_array);
  ftp.WriteData(fb->buf, fb->len);
  ftp.CloseFile();
  esp_camera_fb_return(fb);
  ftp.CloseConnection();
  delay(100);
}

void enviaAlerta(String tipo, String id)
{
  HTTPClient http;
  http.begin(host + "?collection=alertas");
  http.addHeader("Authorization", "Basic " + auth);
  http.addHeader("Content-Type", "application/json");
  int codeResp = http.POST("{\"tipo\" : \"" + tipo + "\", \"idDispositivo\" : \"" + id + "\", \"nombre_archivo\" : \"" + fileName + "\"}");
  if (codeResp > 0)
  {
    if (codeResp == 200)
    {
      Serial.println("Alerta Enviada.");
    }
    else
    {
      Serial.println("Error de respuesta.");
    }
  }
  else
  {
    Serial.println("Error al enviar peticion HTTP.");
  }
}

void initCamera()
{
  config.ledc_channel = LEDC_CHANNEL_0;
  config.ledc_timer = LEDC_TIMER_0;
  config.pin_d0 = Y2_GPIO_NUM;
  config.pin_d1 = Y3_GPIO_NUM;
  config.pin_d2 = Y4_GPIO_NUM;
  config.pin_d3 = Y5_GPIO_NUM;
  config.pin_d4 = Y6_GPIO_NUM;
  config.pin_d5 = Y7_GPIO_NUM;
  config.pin_d6 = Y8_GPIO_NUM;
  config.pin_d7 = Y9_GPIO_NUM;
  config.pin_xclk = XCLK_GPIO_NUM;
  config.pin_pclk = PCLK_GPIO_NUM;
  config.pin_vsync = VSYNC_GPIO_NUM;
  config.pin_href = HREF_GPIO_NUM;
  config.pin_sscb_sda = SIOD_GPIO_NUM;
  config.pin_sscb_scl = SIOC_GPIO_NUM;
  config.pin_pwdn = PWDN_GPIO_NUM;
  config.pin_reset = RESET_GPIO_NUM;
  config.xclk_freq_hz = 20000000;
  config.pixel_format = PIXFORMAT_JPEG;

  if (psramFound())
  {
    config.frame_size = FRAMESIZE_UXGA; //FRAMESIZE_UXGA; // FRAMESIZE_ + QVGA|CIF|VGA|SVGA|XGA|SXGA|UXGA
    config.jpeg_quality = 10;
    config.fb_count = 2;
  }
  else
  {
    config.frame_size = FRAMESIZE_UXGA;
    config.jpeg_quality = 12;
    config.fb_count = 1;
  }
  // Init Camera
  esp_err_t err = esp_camera_init(&config);
  if (err != ESP_OK)
  {
    Serial.printf("Camera init failed with error 0x%x", err);
    return;
  }
  else
  {
    Serial.printf("Camera init Success");
  }
}
