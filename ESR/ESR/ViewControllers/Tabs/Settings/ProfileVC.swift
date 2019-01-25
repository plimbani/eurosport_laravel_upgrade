//
//  ProfileVC.swift
//  ESR
//
//  Created by Pratik Patel on 21/08/18.
//

import UIKit

class ProfileVC: SuperViewController {

    @IBOutlet var btnUpdate: UIButton!
    @IBOutlet var table: UITableView!
    
    var txtEmail: UITextField!
    var txtFirstName: UITextField!
    var txtSurname: UITextField!
    var lblTournament: UILabel!
    var lblLanguage: UILabel!
    var lblRole: UILabel!
    var lblCountry: UILabel!
    
    var fieldList = NSArray()
    var cellList = NSMutableArray()
    
    var heightLabelSelectionCell: CGFloat = 0
    var heightTextFieldCell: CGFloat = 0
    
    // PickerHandlerView
    var pickerHandlerView: PickerHandlerView!
    var tournamentTitleList = [String]()
    
    var countryTitleList = [String]()
    
    var tournamentList = [NSDictionary]()
    var selectedTournamentId = NULL_ID
    var selectedCountryId = NULL_ID
    var selectedLocale = NULL_STRING
    var selectedRole = NULL_STRING
    
    var isRole = false
    var isCountry = false
    
    enum SettingsList: Int {
        case email = 0
        case firstname = 1
        case surname = 2
        case role = 3
        case language = 4
        case country = 5
    }
    
    override func viewDidLoad() {
        super.viewDidLoad()
        initialize()
    }
    
    func initialize(){
        titleNavigationBar.delegate = self
        titleNavigationBar.lblTitle.text = String.localize(key: "title_profile")
        titleNavigationBar.setBackgroundColor()
        
        // Fieldlist
        if let url = Bundle.main.url(forResource: "ProfileList", withExtension: "plist") {
            fieldList = NSArray(contentsOf: url)!
        }
        
        table.tableFooterView = UIView()
        
        // Heights for cell
        _ = cellOwner.loadMyNibFile(nibName: kNiB.Cell.LabelSelectionCell)
        heightLabelSelectionCell = (cellOwner.cell as! LabelSelectionCell).getCellHeight()
        
        _ = cellOwner.loadMyNibFile(nibName: kNiB.Cell.TextFieldCell)
        heightTextFieldCell = (cellOwner.cell as! TextFieldCell).getCellHeight()
        
        pickerHandlerView = getPickerView()
        pickerHandlerView.delegate = self
        self.view.addSubview(pickerHandlerView)
        
        initInfoAlertView(self.view, self)
        
        btnUpdate.isEnabled = false
        btnUpdate.backgroundColor = UIColor.btnDisable
        
        if let userData = ApplicationData.sharedInstance().getUserData() {
            selectedTournamentId = userData.tournamentId
            selectedRole = userData.role
            selectedCountryId = userData.countryId
        }
    }
    
    override func viewWillAppear(_ animated: Bool) {
        sendRequestForTournaments()
    }
    
    //MARK:- Request Methods
    func sendRequestForTournaments() {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        self.view.showProgressHUD()
        ApiManager().getTournaments(success: { result in
            DispatchQueue.main.async {
                
                if let tournamentList = result.value(forKey: "data") as? NSArray {
                    for tournament in tournamentList {
                        self.tournamentTitleList.append((tournament as! NSDictionary).value(forKey: "name") as! String)
                        self.tournamentList.append(tournament as! NSDictionary)
                    }
                }
                
                if self.lblTournament != nil && self.tournamentTitleList.count > 0 {
                    if self.selectedTournamentId != -1 {
                        for i in 0..<self.tournamentList.count {
                            if let serverTournamentId = self.tournamentList[i].value(forKey: "id") as? Int {
                                if serverTournamentId == self.selectedTournamentId {
                                    self.lblTournament.text = self.tournamentList[i].value(forKey: "name") as! String
                                    break
                                }
                            }
                        }
                    } else {
                        self.lblTournament.text = self.tournamentTitleList[0]
                    }
                }
                
                // Sets user role
                if let userDetails = ApplicationData.sharedInstance().getUserData() {
                    if userDetails.role != NULL_STRING {
                        self.lblRole.text = userDetails.role
                    }
                }
                
                // Gets countries list
                self.sendRequestForGetCountries()
            }
            
        }, failure: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
            }
        })
    }
    
    func sendRequestForGetCountries() {
        if APPDELEGATE.reachability.connection == .none {
            self.view.hideProgressHUD()
            return
        }
        
        if ApplicationData.countriesList.count > 0 {
            self.view.hideProgressHUD()
            self.fillCountriesName()
            return
        }
        
        ApiManager().getCountriesList(success: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                
                if let countriesList = result.value(forKey: "countries") as? NSArray {
                    ApplicationData.countriesList = countriesList
                    self.fillCountriesName()
                }
            }
        }, failure: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
            }
        })
    }
    
    func fillCountriesName() {
        countryTitleList.removeAll()
        for country in ApplicationData.countriesList {
            let dic = country as! NSDictionary
            
            var countryName = NULL_STRING
            
            if let name = dic.value(forKey: "name") as? String {
                countryName = name
                countryTitleList.append(name)
            }
            
            if let userDetails = ApplicationData.sharedInstance().getUserData() {
                if userDetails.countryId != NULL_ID && userDetails.countryId == dic.value(forKey: "id") as! Int {
                    selectedCountryId = userDetails.countryId
                    lblCountry.text = countryName
                }
            }
        }
    }
    
    func sendUpdateProfileRequest() {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        self.view.showProgressHUD()
        
        var parameters: [String: Any] = [:]
        parameters["first_name"] = txtFirstName.text!
        parameters["last_name"] = txtSurname.text!
        parameters["tournament_id"] = selectedTournamentId
        parameters["country_id"] = selectedCountryId
        parameters["role"] = selectedRole
        parameters["locale"] = selectedLocale
        
        if let userData = ApplicationData.sharedInstance().getUserData() {
            parameters["user_id"] = userData.id
            
            ApiManager().updateProfile(userId: "\(userData.id)", parameters, success: { result in
                DispatchQueue.main.async {
                    self.view.hideProgressHUD()
                    
                    if let message = result.value(forKey: "message") as? String {
                        self.showInfoAlertView(title: String.localize(key: "alert_title_error"), message: message, requestCode: AlertRequestCode.profileUpdate.rawValue)
                        
                        if let userData = ApplicationData.sharedInstance().getUserData() {
                            userData.tournamentId = self.selectedTournamentId
                            userData.locale = self.selectedLocale
                            userData.firstName = self.txtFirstName.text!
                            userData.surName = self.txtSurname.text!
                            userData.role = self.selectedRole
                            userData.countryId = self.selectedCountryId
                            ApplicationData.sharedInstance().saveUserData(userData)
                            
                            // Notifies app to change language
                            Bundle.set(languageCode: userData.locale)
                            
                            if let mainTabViewController = self.parent!.parent as? MainTabViewController {
                                mainTabViewController.refeshTabTitle()
                            }
                        }
                    }
                }
            }, failure: { result in
                DispatchQueue.main.async {
                    self.view.hideProgressHUD()
                    
                    if result.allKeys.count == 0 {
                        return
                    }
                    
                    if let error = result.value(forKey: "error") as? String {
                        self.showInfoAlertView(title: String.localize(key: "alert_title_error"), message: error)
                    }
                }
            })
        }
    }
    
    func updateUpdateBtn(){
        btnUpdate.isEnabled = false
        btnUpdate.backgroundColor = UIColor.btnDisable
        
        if let text = txtFirstName.text {
            if text.trimmingCharacters(in: .whitespacesAndNewlines).isEmpty {
                return
            }
        }
        
        if let text = txtSurname.text {
            if text.trimmingCharacters(in: .whitespacesAndNewlines).isEmpty {
                return
            }
        }
        
        if selectedRole == NULL_STRING {
            return
        }
        
        if selectedCountryId == NULL_ID {
            return
        }
        
        btnUpdate.isEnabled = true
        btnUpdate.backgroundColor = UIColor.btnYellow
    }
    
    func getRefreshedLanguageList() -> [String] {
        return [String.localize(key: "English"), String.localize(key: "French"), String.localize(key: "Italian"), String.localize(key: "German"), String.localize(key: "Dutch"), String.localize(key: "Czech"), String.localize(key: "Danish"), String.localize(key: "Polish")]
    }
    
    @objc func textFieldDidChange(textField: UITextField){
        updateUpdateBtn()
    }
    
    @IBAction func updateBtnPressed(_ sender: UIButton) {
        sendUpdateProfileRequest()
    }
}

extension ProfileVC: CustomAlertViewDelegate {
    func customAlertViewOkBtnPressed(requestCode: Int) {
        if requestCode == AlertRequestCode.profileUpdate.rawValue {
            self.navigationController?.popViewController(animated: true)
        }
    }
}

extension ProfileVC: PickerHandlerViewDelegate {
    
    func pickerCancelBtnPressed() {}
    
    func pickerDoneBtnPressed(_ title: String) {

        if isRole {
            isRole = false
            
            lblRole.text = title
            selectedRole = title
            lblRole.textColor = .black
        } else if isCountry {
            isCountry = false
            
            lblCountry.text = title
            lblCountry.textColor = .black
            
            if countryTitleList.count > 0 {
                let dic =  ApplicationData.countriesList[pickerHandlerView.selectedPickerPosition] as! NSDictionary
                
                if let countryId = dic.value(forKey: "id") as? Int {
                    selectedCountryId = countryId
                }
            }
        } else {
            lblLanguage.text = title
            lblLanguage.textColor = .black
            
            let array = getRefreshedLanguageList()
            
            for i in 0..<array.count {
                if title == array[i] {
                    selectedLocale = ApplicationData.localeKeyList[i]
                    break
                }
            }
        }
        
        updateUpdateBtn()
    }
}

extension ProfileVC: TitleNavigationBarDelegate {
    func titleNavBarBackBtnPressed() {
        self.navigationController?.popViewController(animated: true)
    }
}

extension ProfileVC : UITableViewDataSource, UITableViewDelegate {
    
    func getCellHeight(_ indexPath: IndexPath) -> CGFloat {
        var height:CGFloat = 0
        let field = fieldList[indexPath.row] as! NSDictionary
        if let rawValue = field.value(forKey: "cellType") as? Int {
            if let cellType = CellType(rawValue: rawValue) {
                switch cellType {
                case .LabelSelectionCell:
                    height = heightLabelSelectionCell
                case .TextFieldCell:
                    height = heightTextFieldCell
                default:
                    print("default")
                }
            }
        }
        return height
    }
    
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        return fieldList.count
    }
    
    func tableView(_ tableView: UITableView, heightForRowAt indexPath: IndexPath) -> CGFloat {
        return getCellHeight(indexPath)
    }
    
    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        
        if cellList.count > indexPath.row {
            return cellList[indexPath.row] as! UITableViewCell
        } else {
            let field = fieldList[indexPath.row] as! NSDictionary
            print(field)
            if let rawValue = field.value(forKey: "cellType") as? Int {
                var cell:UITableViewCell! = nil
                if let cellType = CellType(rawValue: rawValue) {
                    switch(cellType) {
                        case .TextFieldCell:
                            _ = cellOwner.loadMyNibFile(nibName: kNiB.Cell.TextFieldCell)
                            let textFieldCell = cellOwner.cell as! TextFieldCell
                            textFieldCell.record = field
                            textFieldCell.setBorder()
                            textFieldCell.reloadCell()
                            cell = textFieldCell
                            cellList.add(cell)
                            textFieldCell.txtField.addTarget(self, action: #selector(textFieldDidChange(textField:)), for: .editingChanged)
                            //textFieldCell.txtField.delegate = self
                            if indexPath.row == SettingsList.email.rawValue {
                                textFieldCell.txtField.isEnabled = false
                                txtEmail = textFieldCell.txtField
                                txtEmail.textColor = UIColor.txtPlaceholderTxt
                            } else if indexPath.row == SettingsList.firstname.rawValue {
                                txtFirstName = textFieldCell.txtField
                            } else if indexPath.row == SettingsList.surname.rawValue {
                                txtSurname = textFieldCell.txtField
                            }
                            
                            if let userData = ApplicationData.sharedInstance().getUserData() {
                                if txtEmail != nil {
                                    txtEmail.text = userData.email
                                }
                                
                                if txtFirstName != nil {
                                    txtFirstName.text = userData.firstName
                                }
                                
                                if txtSurname != nil {
                                    txtSurname.text = userData.surName
                                }
                            }
                        case .LabelSelectionCell:
                            _ = cellOwner.loadMyNibFile(nibName: kNiB.Cell.LabelSelectionCell)
                            let labelSelectionCell = cellOwner.cell as! LabelSelectionCell
                            labelSelectionCell.record = field
                            labelSelectionCell.containerView.backgroundColor = UIColor.labelSlectionBg
                            labelSelectionCell.reloadCell()
                            cell = labelSelectionCell
                            cellList.add(cell)
                            
                            if indexPath.row == SettingsList.role.rawValue {
                                lblRole = labelSelectionCell.lblTitle
                                lblRole.textColor = .black
                            } else if indexPath.row == SettingsList.country.rawValue {
                                lblCountry = labelSelectionCell.lblTitle
                                lblCountry.textColor = .black
                            } else if indexPath.row == SettingsList.language.rawValue {
                                lblLanguage = labelSelectionCell.lblTitle
                                
                                let localeValues = ApplicationData.sharedInstance().getSelectedLocale()
                                
                                if !localeValues.0.isEmpty {
                                    lblLanguage.text = localeValues.1
                                    selectedLocale = localeValues.1
                                } else {
                                    lblLanguage.text = getRefreshedLanguageList()[0]
                                    selectedLocale = getRefreshedLanguageList()[0]
                                }
                                
                                lblLanguage.textColor = .black
                            }
                            
                        
                        default:
                            print("default")
                    }
                }
                cell.backgroundColor = .clear
                return cell
            }
        }
        return UITableViewCell()
    }
    
    func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
         self.view.endEditing(true)
        
         if indexPath.row == SettingsList.role.rawValue {
            isRole = true
            pickerHandlerView.titleList = ApplicationData.rolesList
         } else if indexPath.row == SettingsList.country.rawValue {
            isCountry = true
            pickerHandlerView.titleList = countryTitleList
         } else if indexPath.row == SettingsList.language.rawValue {
            pickerHandlerView.titleList = getRefreshedLanguageList()
        }
        
         pickerHandlerView.reloadPickerView()
         pickerHandlerView.show()
    }
}

