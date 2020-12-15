//
//  TabTournamentVC.swift
//  ESR
//
//  Created by Pratik Patel on 14/08/18.
//

import UIKit
import MBCircularProgressBar
import SDWebImage

class TabTournamentVC: SuperViewController {

    @IBOutlet var tournamentSelectView: UIView!
    @IBOutlet var lblTournamentDate: UILabel!
    @IBOutlet var lblTournamentName: UILabel!
    
    @IBOutlet var tounamentImgView: UIImageView!
    @IBOutlet var containerViewTop: UIView!
    @IBOutlet var lblSelectedTournamentName: UILabel!
    
    @IBOutlet var daysProgressViewContainer: UIView!
    @IBOutlet var hoursProgressViewContainer: UIView!
    @IBOutlet var minutesProgressViewContainer: UIView!
    @IBOutlet var secondsProgressViewContainer: UIView!
    
    @IBOutlet var btnFinalPlacings: UIButton!
    @IBOutlet var btnTeams: UIButton!
    
    var daysProgressView: MBCircularProgressBarView!
    var hoursProgressView: MBCircularProgressBarView!
    var minutesProgressView: MBCircularProgressBarView!
    var secondsProgressView: MBCircularProgressBarView!
    
    var tournamentList = [Tournament]()
    var titleList = [String]()
    
    var delegate: MainTabViewControllerDelegate?
    
    var tournamentDetailsView: TournamentDetailsView!
    
    @IBOutlet var lblDays: UILabel!
    @IBOutlet var lblHours: UILabel!
    @IBOutlet var lblMinutes: UILabel!
    @IBOutlet var lblSeconds: UILabel!
    
    var seconds = 60
    var timer: Timer?
    var isTimerRunning = false
    
    var selectedPosition = 0
    
    override func viewDidLoad() {
        super.viewDidLoad()
        TestFairy.log(String(describing: self))
        initialize()
    }
    
    deinit {
        NotificationCenter.default.removeObserver(self, name: .internetConnectivity, object: nil)
    }
    
    override func viewDidAppear(_ animated: Bool) {
        ApplicationData.setBorder(containerViewTop, Color: .darkGray, thickness: 1.0, type: ViewBorderType.top)
        ApplicationData.setBorder(containerViewTop, Color: .darkGray, thickness: 1.0, type: ViewBorderType.bottom)
        ApplicationData.setBorder(containerViewTop, Color: .darkGray, thickness: 1.0, type: ViewBorderType.right)
        ApplicationData.setBorder(containerViewTop, Color: .darkGray, thickness: 1.0, type: ViewBorderType.left)
        
        if timer == nil {
            runTimer()
        }
    }
    
    override func viewDidDisappear(_ animated: Bool) {
        if timer != nil {
            timer!.invalidate()
            timer = nil
        }
    }
    
    func initialize() {
        self.navigationController?.isNavigationBarHidden = true
        
        lblDays.text = String.localize(key: "Days")
        lblHours.text = String.localize(key: "Hours")
        lblMinutes.text = String.localize(key: "Minutes")
        lblSeconds.text = String.localize(key: "Seconds")
        
        daysProgressView = getProgressView(maxValue: 30)
        hoursProgressView = getProgressView(maxValue: 24)
        minutesProgressView = getProgressView(maxValue: 60)
        secondsProgressView = getProgressView(maxValue: 60)
        
        daysProgressViewContainer.addSubview(daysProgressView)
        hoursProgressViewContainer.addSubview(hoursProgressView)
        minutesProgressViewContainer.addSubview(minutesProgressView)
        secondsProgressViewContainer.addSubview(secondsProgressView)
        
        if ApplicationData.currentTarget == ApplicationData.CurrentTargetList.EasyMM.rawValue {
            btnFinalPlacings.setBackgroundImage(UIImage.init(named: "btn_yellow"), for: .normal)
            btnTeams.setBackgroundImage(UIImage.init(named: "btn_yellow"), for: .normal)
            btnFinalPlacings.setTitleColor(.white, for: .normal)
            btnTeams.setTitleColor(.white, for: .normal)
        }
        
        // Checks internet connectivity
        setConstraintLblNoInternet(APPDELEGATE.reachability.connection == .none)
        
        let tap = UITapGestureRecognizer(target: self, action: #selector(onSelectTournament(_:)))
        tournamentSelectView.addGestureRecognizer(tap)
        
        // To show/hide internet view in Navigation bar
        NotificationCenter.default.addObserver(self, selector: #selector(showHideNoInternetView(_:)), name: .internetConnectivity, object: nil)
        
        tournamentDetailsView = APPDELEGATE.mainTabVC?.tournamentDetailsView
        sendRequestGetTournaments()
    }
    
    func runTimer() {
        timer = Timer.scheduledTimer(timeInterval: 1, target: self, selector: #selector(updateTimer), userInfo: nil, repeats: true)
    }
    
    func getProgressView(maxValue: CGFloat) -> MBCircularProgressBarView {
        let progressBarView = MBCircularProgressBarView()
        progressBarView.backgroundColor = .white
        progressBarView.frame = CGRect(x: 0, y: 0, width: 60, height: 60)
        // Progress
        progressBarView.progressColor = UIColor.AppColor()
        progressBarView.progressStrokeColor = .clear
        progressBarView.progressAngle = 100
        progressBarView.progressCapType = 2
        // Line
        progressBarView.progressLineWidth = 6.0
        progressBarView.emptyLineWidth = 2.0
        progressBarView.maxValue = maxValue
        progressBarView.value = 0
        // Fonts
        progressBarView.valueFontName = Font.HELVETICA_BOLD
        progressBarView.valueFontSize = 21
        progressBarView.fontColor = .AppColor()
        // Unit
        progressBarView.unitString = NULL_STRING
        return progressBarView
    }
    
    func updateTournamentDetails() {
        
        if let selectedTournament = ApplicationData.sharedInstance().getSelectedTournament() {
            if selectedTournament.logo != NULL_STRING {
                tounamentImgView.sd_setImage(with: URL(string: selectedTournament.logo), placeholderImage: UIImage(named: "globe"))
            } else {
                tounamentImgView.image = UIImage(named: "globe")
            }
            
            lblTournamentName.text = selectedTournament.name
            lblSelectedTournamentName.text = selectedTournament.name
            
            var tournamentDate = NULL_STRING
            
            let calendar = Calendar.current
            let startTournamentMonthStr = selectedTournament.startDateObj.getMonthName()
            let endTournamentMonthStr = selectedTournament.endDateObj.getMonthName()
            let endDay = calendar.component(.day, from: selectedTournament.endDateObj)
            let startDay = calendar.component(.day, from: selectedTournament.startDateObj)
            let yearString = calendar.component(.year, from: selectedTournament.startDateObj)
            
            if startTournamentMonthStr == endTournamentMonthStr {
                tournamentDate = "\(startDay) - \(endDay) \(startTournamentMonthStr) \(yearString)"
            }else{
                tournamentDate = "\(startDay) \(startTournamentMonthStr) - \(endDay) \(endTournamentMonthStr) \(yearString)"
            }
            
            lblTournamentDate.text = tournamentDate
            convertToCountdownTime()
        }
    }
    
    func convertToCountdownTime() {
        
        if let selectedTournament = ApplicationData.sharedInstance().getSelectedTournament() {
            if let tournamentStartTime = ApplicationData.getCountDownTime(selectedTournament.tournamentStartTime, dateFormat: kDateFormat.format3) {
                
                let calendar = NSCalendar.current
                let unitFlags = Set<Calendar.Component>([.day, .hour, .minute, .second])
                let dateComponents: DateComponents? = calendar.dateComponents(unitFlags, from: Date(),  to: tournamentStartTime)
                
                if let components = dateComponents {
                    if (components.day ?? 0) > 0 {
                        daysProgressView.value = CGFloat(components.day ?? 0)
                    } else {
                        daysProgressView.value = 0
                    }
                    if Int(components.minute ?? 0) > 0 {
                        minutesProgressView.value = CGFloat(components.minute ?? 0)
                    } else {
                        minutesProgressView.value = 0
                    }
                    if Int(components.hour ?? 0) > 0 {
                        hoursProgressView.value = CGFloat(components.hour ?? 0)
                    } else {
                        hoursProgressView.value = 0
                    }
                    if Int(components.second ?? 0) > 0 {
                        secondsProgressView.value = CGFloat(components.second ?? 0)
                    } else {
                        secondsProgressView.value = 0
                    }
                }
            }
        }
    }
    
    @objc func updateTimer() {
        convertToCountdownTime()
    }
    
    @objc func onSelectTournament(_ sender : UITapGestureRecognizer) {
        TestFairy.log(String(describing: self) + " onSelectTournament")
        showPickerVC(selectedPosition: selectedPosition, titleList: titleList, delegate: self)
    }
    
    @objc func showHideNoInternetView(_ notification: NSNotification) {
        if notification.userInfo != nil {
            if let isShow = notification.userInfo![kNotification.isShow] as? Bool {
                setConstraintLblNoInternet(isShow)
            }
        }
    }
    
    @IBAction func onAgeCategoriesPressed(_ sender: UIButton) {
        TestFairy.log(String(describing: self) + " onAgeCategoriesPressed")
        if ApplicationData.sharedInstance().isTournamentInPreview() {
            showCustomAlertVC(title: String.localize(key: "alert_title_preview"), message: String.localize(key: "alert_preview_tournament"), requestCode: 100, delegate: self)
            return
        }
        
        // let viewController = Storyboards.Teams.instantiateCategoryListVC()
        // viewController.isFromTournament = true
        // self.navigationController?.pushViewController(viewController, animated: true)
        
        delegate!.mainTabViewControllerSelectTab(TabIndex.tabAgeCategories.rawValue)
    }
    
    @IBAction func onTeamPressed(_ sender: UIButton) {
        TestFairy.log(String(describing: self) + " onTeamPressed")
        if ApplicationData.sharedInstance().isTournamentInPreview() {
            showCustomAlertVC(title: String.localize(key: "alert_title_preview"), message: String.localize(key: "alert_preview_tournament"), requestCode: 102, delegate: self)
            return
        }
        
        delegate!.mainTabViewControllerSelectTab(TabIndex.tabTeams.rawValue)
    }
    
    @IBAction func onTournamentDetailsPressed(_ sender: UIButton) {
        TestFairy.log(String(describing: self) + " onTournamentDetailsPressed")
        if let selectedTournament = ApplicationData.sharedInstance().getSelectedTournament() {
            tournamentDetailsView.nameString = selectedTournament.firstName + " " + selectedTournament.lastName
            tournamentDetailsView.contactString = selectedTournament.telephone
            tournamentDetailsView.reload()
        }
        
        tournamentDetailsView.show()
    }
    
    func refreshTournamentPosition() {
        var defaultTournament: Tournament?
        var selectedIndex = 0
        
        for i in 0..<tournamentList.count {
            
            let tournament = tournamentList[i]
            
            if let selectedTournament = ApplicationData.sharedInstance().getSelectedTournament() {
                if selectedTournament.id == tournament.id {
                    defaultTournament = tournament
                    selectedIndex = i
                    break
                }
            }
        }
        
        self.tournamentList.remove(at: selectedIndex)
        
        if let defaultTournamentValue = defaultTournament {
            self.tournamentList.insert(defaultTournamentValue, at: 0)
        }
        
        self.titleList.removeAll()
        for tournament in self.tournamentList {
            self.titleList.append(tournament.name)
        }
    }
    
    func sendRequestGetTournaments() {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        var parameters: [String: Any] = [:]
        if let userData = ApplicationData.sharedInstance().getUserData() {
            parameters["user_id"] = userData.id
        }
        
        self.view.showProgressHUD()
        ApiManager().getFavTournaments(parameters, success: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                
                self.titleList.removeAll()
                
                var isDefaultTournament = false
                var defaultTournament: Tournament?
                
                if let tournamentList = result.value(forKey: "data") as? NSArray {
                    for i in 0..<tournamentList.count {
                        let dicTournament = tournamentList[i] as! NSDictionary
                        let tournament = ParseManager.parseFavTournament(dicTournament)
                        
                        if let selectedTournament = ApplicationData.sharedInstance().getSelectedTournament() {
                            if selectedTournament.id == tournament.id {
                                ApplicationData.sharedInstance().saveSelectedTournament(tournament)
                                self.lblSelectedTournamentName.text = tournament.name
                                isDefaultTournament = true
                            }
                        } else {
                            if tournament.isDefault == 1 {
                                ApplicationData.sharedInstance().saveSelectedTournament(tournament)
                                
                                if let userData = ApplicationData.sharedInstance().getUserData() {
                                    userData.tournamentId = tournament.id
                                    ApplicationData.sharedInstance().saveUserData(userData)
                                }
                                
                                isDefaultTournament = true
                            }
                        }

                        if isDefaultTournament {
                            defaultTournament = tournament
                            isDefaultTournament = false
                        } else {
                            self.tournamentList.append(tournament)
                        }
                    }
                    
                    // Sort array by start date
                    self.tournamentList.sort(by: { (t1, t2) -> Bool in
                        return (t1.startDateObj.timeIntervalSinceNow > t2.startDateObj.timeIntervalSinceNow)
                    })
                    
                    if let defaultTournamentValue = defaultTournament {
                        self.tournamentList.insert(defaultTournamentValue, at: 0)
                    }
                    
                    for tournament in self.tournamentList {
                        self.titleList.append(tournament.name)
                    }
                    
                    self.updateTournamentDetails()
                }
            }
        }, failure: { result in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                
                if result.allKeys.count == 0 {
                    return
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
        })
    }
}

extension TabTournamentVC: CustomAlertVCDelegate {
    func customAlertVCOkBtnPressed(requestCode: Int) {
        print("REQ CODE: \(requestCode)")
    }
}

extension TabTournamentVC: PickerVCDelegate {
    func pickerVCDoneBtnPressed(title: String, lastPosition: Int) {
        lblSelectedTournamentName.text = title
        selectedPosition = lastPosition
        
        let dicSelectedTournament = self.tournamentList[lastPosition]
        ApplicationData.sharedInstance().saveSelectedTournament(dicSelectedTournament)
        updateTournamentDetails()
    }
    
    func pickerVCCancelBtnPressed() {}
}

