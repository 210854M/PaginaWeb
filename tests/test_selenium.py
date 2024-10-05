from selenium import webdriver
from selenium.webdriver.chrome.options import Options
import logging
from selenium.webdriver.remote.remote_connection import LOGGER

# Habilitar registros de Selenium
LOGGER.setLevel(logging.DEBUG)

# Configura opciones para Chrome
chrome_options = Options()
chrome_options.add_argument("--headless")  # Ejecutar en modo sin cabeza (sin int$
chrome_options.add_argument("--no-sandbox")
chrome_options.add_argument("--disable-dev-shm-usage")

# Inicializa el driver de Chrome
driver = webdriver.Chrome(options=chrome_options)  # Usa solo chrome_options

try:
    # Navega a tu página web
    driver.get("http://13.83.4.217")  # Asegúrate de incluir 'http://'

    # Verifica si la página cargó correctamente
    if "AED" in driver.title:  # Cambia esto por el título esperado
        print("La prueba se ejecutó correctamente. La página cargó.")
    else:
        print("La página no se cargó correctamente. Título actual:", driver.title)

except Exception as e:
    print(f"Ocurrió un error: {str(e)}")  # Imprime el mensaje de error completo
finally:
    driver.quit()  # Cierra el navegador