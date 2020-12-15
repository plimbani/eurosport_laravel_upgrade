//
//  CategoryListVC.swift
//  ESR
//
//  Created by Pratik Patel on 07/12/18.
//

import UIKit

class CategoryListVC: SuperViewController {

    @IBOutlet var txtSearch: UITextField!
    @IBOutlet var table: UITableView!
    @IBOutlet var searchView: UIView!
    @IBOutlet var imgSearch: UIImageView!
    
    @IBOutlet var heightConstraintSeachView: NSLayoutConstraint!
    @IBOutlet var heightConstraintTitleNavigationBar: NSLayoutConstraint!
    
    var ageCategoriesList = NSMutableArray()
    var ageCategoriesFilterList = NSMutableArray()
    var heightAgeCategoryCell: CGFloat = 0
    var isFromTournament = false
    var tournamentId = NULL_ID
    var isSearch = false
    
    @IBOutlet var lblNoData: UILabel!
    
    var isDataAvailable: Bool = false {
        didSet {
            table.isHidden = !isDataAvailable
            lblNoData.isHidden = isDataAvailable
        }
    }
    
    var rotateToPortrait = false
    
    override func viewDidLoad() {
        super.viewDidLoad()
        TestFairy.log(String(describing: self))
        initialize()
    }
    
    override func viewWillAppear(_ animated: Bool) {
        sendAgeCategoriesRequest()
        
        if rotateToPortrait {
            APPDELEGATE.deviceOrientation = .portrait
            let valueOrientation = UIInterfaceOrientation.portrait.rawValue
            UIDevice.current.setValue(valueOrientation, forKey: "orientation")
            UIViewController.attemptRotationToDeviceOrientation()
            self.tabBarController?.tabBar.isHidden = false
            rotateToPortrait = false
            
            if let mainTabViewController = self.parent!.parent as? MainTabViewController {
                mainTabViewController.hideTabbar(flag: false)
            }
        }
    }
    
    func initialize() {
        let adjustForTabbarInsets: UIEdgeInsets = UIEdgeInsetsMake(0, 0, 60, 0)
        table.contentInset = adjustForTabbarInsets
        table.scrollIndicatorInsets = adjustForTabbarInsets
        
        txtSearch.placeholder = String.localize(key: "placeholder_search_tab_category")
        txtSearch.setLeftPaddingPoints(35)
        txtSearch.returnKeyType = .done
        txtSearch.layer.cornerRadius = (txtSearch.frame.size.height / 2)
        txtSearch.clipsToBounds = true
        txtSearch.font = UIFont(name: Font.HELVETICA_REGULAR, size: Font.Size.commonTextFieldTxt)
        txtSearch.addTarget(self, action: #selector(textFieldDidChange(textField:)), for: .editingChanged)
        
        // Height for cell
        _ = cellOwner.loadMyNibFile(nibName: kNiB.Cell.AgeCategoryCell)
        heightAgeCategoryCell = (cellOwner.cell as! AgeCategoryCell).getCellHeight()
        
        if isFromTournament {
            heightConstraintSeachView.constant = 0
            titleNavigationBar.lblTitle.text = String.localize(key: "title_final_placing")
            titleNavigationBar.delegate = self
            titleNavigationBar.setBackgroundColor()
            searchView.updateConstraints()
            imgSearch.image = UIImage()
        } else {
            heightConstraintTitleNavigationBar.constant = 0
        }
        
        hideKeyboardWhenTappedAround()
    }
    
    @objc func textFieldDidChange(textField: UITextField) {
        if let text = txtSearch.text {
            if text.isEmpty {
                isSearch = false
                table.reloadData()
                return
            }
            
            isSearch = true
            
            ageCategoriesFilterList = NSMutableArray.init(array: ageCategoriesList.filter({
                (($0 as! NSDictionary).value(forKey: "category_age") as! String).contains(text) ||
                (($0 as! NSDictionary).value(forKey: "category_age") as! String).lowercased().contains(text)
            }))
            
            table.reloadData()
        }
    }
    
    func sendAgeCategoriesRequest() {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        self.view.showProgressHUD()
        var parameters: [String: Any] = [:]
        
        if let selectedTournament = ApplicationData.sharedInstance().getSelectedTournament() {
            parameters["tournament_id"] = selectedTournament.id
        }
        
        ApiManager().getAgeCategories(parameters, success: { (result) in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                
                if let data = result.value(forKey: "data") as? NSArray {
                    self.ageCategoriesList = NSMutableArray.init(array: data)
                }
                
                self.table.reloadData()
                self.isDataAvailable = (self.ageCategoriesList.count != 0)
            }
        }) { (result) in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                self.isDataAvailable = false
                
                if result.allKeys.count > 0 {
                    if let status_code = result.value(forKey: "status_code") as? Int {
                        if status_code == 500 {
                            if let msg = result.value(forKey: "tournament_expired") as? String {
                                self.showCustomAlertVC(title: String.localize(key: "alert_title_error"), message: msg, requestCode: AlertRequestCode.tournamentExpire.rawValue, delegate: self)
                            }
                        }
                    }
                    
                    if let error = result.value(forKey: "error") as? String {
                        if error == "token_expired"{
                            USERDEFAULTS.set(nil, forKey: kUserDefaults.token)


                            if let keyWindow = UIApplication.shared.windows.first(where: { $0.isKeyWindow }) {
                                keyWindow.rootViewController = UINavigationController(rootViewController:  Storyboards.Main.instantiateLandingVC())
                            }
                        }
                    }
                }
            }
        }
    }

}

extension CategoryListVC: CustomAlertVCDelegate {
    func customAlertVCOkBtnPressed(requestCode: Int) {
        if requestCode == AlertRequestCode.tournamentExpire.rawValue {
            NotificationCenter.default.post(name: .goToTabFollow, object: nil)
        }
    }
}

extension CategoryListVC: TitleNavigationBarDelegate {
    func titleNavBarBackBtnPressed() {
        self.navigationController?.popViewController(animated: true)
    }
}

extension CategoryListVC: AgeCategoryCellDelegate {
    func ageCategoriesCellBtnViewSchedulePressed(_ indexPath: IndexPath) {
        let viewController = Storyboards.Main.instantiateViewScheduleImageVC()
        viewController.isFromTabTeamsVC = true
        var ageCategoryId = -1
        
        if isSearch {
            
            /*if let imgURLValue = (ageCategoriesFilterList[indexPath.row] as! NSDictionary).value(forKey: "graphic_image") as? String {
                viewController.imgURL = imgURLValue
            }*/
            
            if let id = (ageCategoriesFilterList[indexPath.row] as! NSDictionary).value(forKey: "id") as? Int {
                ageCategoryId = id
            }
        } else {
            /*if let imgURLValue = (ageCategoriesList[indexPath.row] as! NSDictionary).value(forKey: "graphic_image") as? String {
                viewController.imgURL = imgURLValue
            }*/
            
            if let id = (ageCategoriesList[indexPath.row] as! NSDictionary).value(forKey: "id") as? Int {
                ageCategoryId = id
            }
        }
        
        sendGetViewGraphicImageRequest(ageGroupId: ageCategoryId, viewController: viewController)
    }
    
    func sendGetViewGraphicImageRequest(ageGroupId: Int, viewController: UIViewController) {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        var parameters: [String: Any] = [:]
        parameters["age_category"] = "\(ageGroupId)"
        
        self.view.showProgressHUD()
        ApiManager().getViewGraphicImage(parameters, success: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                
                if let imgURL = result as? String {
                    (viewController as! ViewScheduleImageVC).base64String = imgURL
                    self.navigationController?.pushViewController(viewController, animated: true)
                }
                
                
            }
        }, failure: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
            }
        })
    }
}

extension CategoryListVC: UITableViewDataSource, UITableViewDelegate {
    
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        
        if isSearch {
            return ageCategoriesFilterList.count
        }
        
        return ageCategoriesList.count
    }
    
    func tableView(_ tableView: UITableView, heightForRowAt indexPath: IndexPath) -> CGFloat {
        return heightAgeCategoryCell
    }
    
    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        var cell = tableView.dequeueReusableCell(withIdentifier: "AgeCategoryCell") as? AgeCategoryCell
        if cell == nil {
            _ = cellOwner.loadMyNibFile(nibName: "AgeCategoryCell")
            cell = cellOwner.cell as? AgeCategoryCell
        }
        
        cell?.indexPath = indexPath
        cell?.delegate = self
        
        if isSearch {
            cell?.record = ageCategoriesFilterList[indexPath.row] as! NSDictionary
        } else {
            cell?.record = ageCategoriesList[indexPath.row] as! NSDictionary
        }
        
        cell?.reloadCell()
        return cell!
    }
    
    func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
        TestFairy.log(String(describing: self) + " didSelectRowAt")
        self.view.endEditing(true)
        txtSearch.resignFirstResponder()
        var dic: NSDictionary!
        
        if isSearch {
            dic = (ageCategoriesFilterList[indexPath.row] as! NSDictionary)
        } else {
            dic = (ageCategoriesList[indexPath.row] as! NSDictionary)
        }
        
        if isFromTournament {
            let viewController = Storyboards.Tournament.instantiateFinalPlacingsVC()
            viewController.ageCategoryId = dic.value(forKey: "id") as! Int
            self.navigationController?.pushViewController(viewController, animated: true)
            return
        }
        
        let viewController = Storyboards.Teams.instantiateTeamListingVC()
        viewController.isClubsCategoryTeam = true
        viewController.dic = dic
        self.navigationController?.pushViewController(viewController, animated: true)
    }
}
