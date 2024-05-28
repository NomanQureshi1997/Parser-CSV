# The webdriver module provides the API for browser automation. It allows you to interact with web elements, navigate web pages, and perform various actions on them.
from selenium import webdriver
# The Keys module provides special keys like ENTER, TAB, ARROW_UP, etc., which can be used to simulate keyboard actions while interacting with web elements.
# he NoSuchElementException class is raised when an element is not found on the web page, typically used for handling scenarios when an expected element is missing.
# The By module provides mechanisms to locate elements on a web page using different locator strategies like ID, CLASS_NAME, CSS_SELECTOR, etc.
from selenium.webdriver.common.by import By
# The time module provides various functions for working with time-related tasks, such as sleeping for a specified duration, measuring elapsed time, etc.
import time
from selenium.webdriver.support import expected_conditions as EC
# Your LinkedIn credentials
ACCOUNT_EMAIL = ""
ACCOUNT_PASSWORD = ""
custom_message = "It's great connecting with you. How have you been?"
# Path to ChromeDriver executable
chrome_driver_path = "C:\\chromedriver.exe"  # Assuming the ChromeDriver executable is named chromedriver.exe

# Optional - Keep the browser open if the script crashes.
chrome_options = webdriver.ChromeOptions()
chrome_options.add_experimental_option("detach", True)

# Create and configure the Chrome webdriver with the specified path to ChromeDriver and options
driver = webdriver.Chrome(options=chrome_options)

def login_to_linkedin(username, password):
    driver.get("https://www.linkedin.com/login")
    username_field = driver.find_element(By.ID,"username")
    password_field = driver.find_element(By.ID,"password")
    login_button = driver.find_element(By.CSS_SELECTOR,".login__form_action_container button")
    username_field.send_keys(username)
    password_field.send_keys(password)
    login_button.click()
    
login_to_linkedin(ACCOUNT_EMAIL,ACCOUNT_PASSWORD)
time.sleep(30)
# Open LinkedIn Jobs page
driver.get("https://www.linkedin.com/mynetwork/invite-connect/connections")

last_height = driver.execute_script("return document.body.scrollHeight")

while True:
    # Scroll down to bottom
    driver.execute_script("window.scrollTo(0, document.body.scrollHeight);")
    
    # Wait to load page
    time.sleep(2)  # Adjust sleep time as needed
    
    # Calculate new scroll height and compare with last scroll height
    new_height = driver.execute_script("return document.body.scrollHeight")
    if new_height == last_height:
        break
    last_height = new_height

# Get Listings
time.sleep(5)
all_listings = driver.find_elements(By.CSS_SELECTOR, "li.mn-connection-card")  # Find all job listings
print('all_listings:', all_listings)

time.sleep(2)

i = 0
for listing in all_listings:
    i += 1
    if i > 2:
        break
    print('i: ',i)
    try:
        message_button = listing.find_element(By.CSS_SELECTOR, "button[aria-label^='Send a message to']")
        time.sleep(5) 
        message_button.click()
        time.sleep(5) 
        paragraphs = driver.find_elements(by=By.TAG_NAME, value="p")
        paragraphs[-5].send_keys(custom_message)
        time.sleep(5)
        # try:
        #     close_button = driver.find_elements(By.CSS_SELECTOR, "header.msg-overlay-conversation-bubble-header")
        #     print('close_button =>',close_button)
        # except:
        #     pass
        # Find the div with the specific class (adjust the selector as needed)
        text_div = driver.find_element(By.CSS_SELECTOR, "div.msg-form__contenteditable")

        # Sleep for 2 seconds to wait for the elements to load (if necessary)
        time.sleep(2)

        # Find all <p> tags within the div
        p_tags = text_div.find_elements(By.TAG_NAME, "p")

        # Clear all <p> tags within the div
        for p in p_tags:
            driver.execute_script("arguments[0].innerHTML = '';", p)
        # input_field.send_keys(custom_message)
        # send =  driver.find_element(By.CSS_SELECTOR,"button.msg-form__send-button")
        # send.click()
        # time.sleep(2)
        # close = driver.find_elements(By.CSS_SELECTOR,"header.msg-overlay-conversation-bubble-header div.msg-overlay-bubble-header__controls button.artdeco-button--circle")
        # close[1].click()
    except:
        pass




