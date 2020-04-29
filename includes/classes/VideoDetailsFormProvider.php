<?php
/**
 * Created by PhpStorm.
 * User: georgefalkovich
 * Date: 15/03/2020
 * Time: 20:47
 */

class VideoDetailsFormProvider
{
    private $con;

    public function __construct($con)
    {
        $this->con = $con;
    }

    public function createUploadForm() {
        $fileInput = $this->createFileInput();
        $titleInput = $this->createTitleInput();
        $descriptionInput = $this->createDescriptionInput();
        $privacyInput = $this->createPrivacyInput();
        $categoriesInput = $this->createCategoriesInput();
        $uploadButton = $this->createUploadButton();
        return "<form action='processing.php' method='POST' enctype='multipart/form-data'>
                  $fileInput 
                    $titleInput
                     $descriptionInput
                       $categoriesInput
                        $privacyInput
                          $uploadButton
                    </form>";
    }

    private function createFileInput() {
        return "<div class=\"form-group\">
                    <label for=\"exampleFormControlFile1\">Example file input</label>
                         <input type=\"file\" class=\"form-control-file\" id=\"exampleFormControlFile1\" name='fileInput' required>
                              </div>";
    }

    private function createTitleInput() {
        return "<div class=\"form-group\">
                    <input class=\"form-control\" type=\"text\" placeholder=\"Title\" name='titleInput'>
                        </div>";
    }

    private function createDescriptionInput() {
        return "<div class=\"form-group\">
                    <textarea class=\"form-control\" placeholder=\"Description\" name='descriptionInput' rows='3'></textarea>
                        </div>";
    }

    private function createPrivacyInput() {
        return "<div class=\"form-group\">
                    <select class=\"form-control\" name='privaceInput'>
                    <option value='1'>Public</option>
                      <option value='0'>Private</option>
                            </select>
                                </div>";
    }

    private function createCategoriesInput() {
        $query = $this->con->prepare("SELECT *  FROM categories");
        $query->execute();
        $html = "<div class=\"form-group\">
                    <select class=\"form-control\" name='categoryInput'>";
        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $id = $row['id'];
            $html .= "<option value='$id'>".$row['name']."</option>";
        }

        $html .= "</select></div>";
        return $html;
    }

    private function createUploadButton() {
        return "<button class='btn btn-primary' name='uploadButton' type='submit'>Upload</button>";
    }

    public function createEditDetailsForm($video) {
        $titleInput = $this->createTitleInput($video->getTitle());
        $descriptionInput = $this->createDescriptionInput($video->getDescription());
        $privacyInput = $this->createPrivacyInput($video->getPrivacy());
        $categoriesInput = $this->createCategoriesInput($video->getCategory());
        $saveButton = $this->createSaveButton();
        return "<form method='POST'>
                    $titleInput
                    $descriptionInput
                    $privacyInput
                    $categoriesInput
                    $saveButton
                </form>";
    }


    private function createSaveButton() {
        return "<button type='submit' class='btn btn-primary' name='saveButton'>Save</button>";
    }
}