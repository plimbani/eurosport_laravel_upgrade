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
    
    var fieldList = NSArray()
    var cellList = NSMutableArray()
    
    var heightLabelSelectionCell: CGFloat = 0
    var heightTextFieldCell: CGFloat = 0
    
    // PickerHandlerView
    var pickerHandlerView: PickerHandlerView!
    var tournamentTitleList = [String]()
    
    var tournamentList = [NSDictionary]()
    var selectedTournamentId = NULL_ID
    var selectedLocale = NULL_STRING
    
    // var isTournament = false
    
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
        
        if let userData = ApplicationData.sharedInstance().getUserData() {
            selectedTournamentId = userData.tournamentId
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
                self.view.hideProgressHUD()
                
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
            }
            
        }, failure: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
            }
        })
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
        
        btnUpdate.isEnabled = true
        btnUpdate.backgroundColor = UIColor.btnYellow
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

            lblLanguage.text = title
            lblLanguage.textColor = .black
            
            for i in 0..<ApplicationData.languageList.count{
                if title == ApplicationData.languageList[i] {
                    selectedLocale = ApplicationData.localeKeyList[i]
                    break
                }
            }
       // }
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
                            if indexPath.row == 0 {
                                textFieldCell.txtField.isEnabled = false
                                txtEmail = textFieldCell.txtField
                                txtEmail.textColor = UIColor.txtPlaceholderTxt
                            } else if indexPath.row == 1 {
                                txtFirstName = textFieldCell.txtField
                            } else if indexPath.row == 2 {
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
                        
                            /*if indexPath.row == 3 {
                                lblTournament = labelSelectionCell.lblTitle
                                lblTournament.textColor = .black
                            } else if indexPath.row == 4 {*/
                                lblLanguage = labelSelectionCell.lblTitle
                                lblLanguage.textColor = .black
                                
                                let localeValues = ApplicationData.sharedInstance().getSelectedLocale()
                                
                                if !localeValues.0.isEmpty {
                                    lblLanguage.text = localeValues.1
                                    selectedLocale = localeValues.1
                                } else {
                                    lblLanguage.text = ApplicationData.languageList[0]
                                    selectedLocale = ApplicationData.localeKeyList[0]
                                }
                           // }
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
        let field = fieldList[indexPath.row] as! NSDictionary
        if let rawValue = field.value(forKey: "cellType") as? Int {
            if let cellType = CellType(rawValue: rawValue) {
                if cellType == .LabelSelectionCell {
                    self.view.endEditing(true)
                    
//                    if indexPath.row == 3 {
//                        pickerHandlerView.titleList = tournamentTitleList
//                        isTournament = true
//                    } else {
                        pickerHandlerView.titleList = ApplicationData.languageList
                        // isTournament = false
                    //}
                    
                    pickerHandlerView.reloadPickerView()
                    pickerHandlerView.show()
                }
            }
        }
    }
}

