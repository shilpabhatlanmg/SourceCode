<?php
return [
    //Start Js Msgs.	
    'ConfirmDelete' => 'Are you sure you want to delete this record?',
    'ConfirmChangeStatus' => 'Are you sure you want to change status?',
    'FieldRequired' => 'This field is required.',
    'ConfirmPasswordError' => 'Password must be same.',
    'EndDateShouldGreaterOrEqual' => 'End Date should be greater than or equal to Start Date!',
    'EnterDescription' => 'Please enter description text!',
    'EnterSpecifications' => 'Please enter specifications text!',
    'ConfirmRevertTransaction' => 'Are you sure you want to revert this transaction?',
    //End Js Msgs.
    
    
    //Common Msgs
    'LoginInvalid' => 'Email and/or Password invalid!',
    'LogoutSuccess' => 'You have logout successfully!',
    
    'FileNotValid' => 'Uploaded file is not valid!',
    'ImageNotSaved' => 'Uploaded image not saved!',
    'FileNotSaved' => 'Uploaded file not saved!',
    'FileNotExist' => 'File does not exist!',

    'OtpSentMobileOrMail' => 'OTP Sent your mobile or email id',
    'OtpSentMail' => 'OTP sent at your mail ID',

    'StatusChanged' => 'Status changed successfully !!!',
    
    'NoRecordFound' => 'Record not found!',
    //End Common Msgs.
    
    
    //Admin Msgs    
    'DetailUpdated' => 'Detail has been updated successfully!',
    'DetailNotUpdated' => 'Detail has been not updated successfully!',
    'PasswordNotMatch' => 'Old password does not match!',
    'PasswordChanged' => 'Password has been changed successfully!',

    'OrganizationAdded' => 'Organization successfully added!',
    'OrganizationNotAdded' => 'Organization not added!',
    'OrganizationDeleted' => 'Organization successfully deleted!',
    'OrganizationNotDeleted' => 'This Organization cannot be deleted as it is associated with existing locations!',
    'OrganizationUpdated' => 'Organization updated successfully!',
    'OrganizationNotUpdated' => 'Organization Not updated successfully!',

    'SecurityUpdated' => 'Security updated successfully!',
    'SecurityDelete' => 'Security Deleted successfully!',
    'SecurityNotDeleted' => 'Security Not Deleted successfully!',

    'PremiseAdded' => 'Building successfully added!',
    'PremiseNotAdded' => 'Building not added!',
    'PremiseDeleted' => 'Building successfully deleted!',
    'PremiseNotDeleted' => 'This Building cannot be deleted as it is associated with existing locations!',
    'PremiseUpdated' => 'Building updated successfully!',

    'LocationAdded' => 'Building Section successfully added!',
    'LocationNotAdded' => 'Building Section not added!',
    'LocationDeleted' => 'Building Section successfully deleted!',
    'LocationNotDeleted' => 'This Building Section cannot be deleted as it is associated with existing beacons!',
    'LocationUpdated' => 'Building Section updated successfully!',
    
    'BeaconAdded' => 'Beacon successfully added!',
    'BeaconNotAdded' => 'Beacon not added!',
    'BeaconMinorId' => 'The minor id has already been taken.',
    'BeaconDeleted' => 'Beacon successfully deleted!',
    'BeaconNotDeleted' => 'This Beacon cannot be deleted as it is associated with existing users!',
    'BeaconUpdated' => 'Beacon updated successfully!',
        
    'PageAdded' => 'Page successfully added!',
    'PageNotSaved' => 'Page could not saved!',    
    'PageUpdated' => 'Page successfully updated!',   
    'PageNotUpdated' => 'Page could not be updated!',  
    'PageDeleted' => 'Page has been deleted successfully!',

    'TestimonialAdded' => 'Testimonial successfully added!',
    'TestimonialNotAdded' => 'Testimonial not added!',
    'TestimonialDeleted' => 'Testimonial successfully deleted!',
    'TestimonialNotDeleted' => 'This Testimonial cannot be deleted as it is associated with existing users!',
    'TestimonialUpdated' => 'Testimonial updated successfully!',

    'SubscriptionAdded' => 'Subscription successfully added!',
    'SubscriptionNotAdded' => 'Subscription not added!',
    'SubscriptionDeleted' => 'Subscription successfully deleted!',
    //'SubscriptionNotDeleted' => 'Subscription not deleted!',
    'SubscriptionNotDeleted' => 'Subscription not deleted because already assigned organization : {{COUNT}}',
    'SubscriptionUpdated' => 'Subscription updated successfully!',
    'SubscriptionNotUpdated' => 'Subscription not Updated because already assigned total organization : {{COUNT}}',
    'SubscriptionMonth' => 'Plan cannot be created for more than 12 Monthly Payments',
    'SubscriptionYear' => 'Plan cannot be created for more than 1 Yearly Payment',

    'SubscriptionPeopleAllow' => 'You have exceeded the limit! Total people allowed {{COUNT}}.',
    'SubscriptionBuildingAllow' => 'You have exceeded the limit! Total Building allowed {{COUNT}}.',
    
    'VerifyCaptcha' => 'Please verify captcha first!',
    
    'MasterRecordAdded' => 'Master Record successfully added!',
    'MasterRecordNotDeleted' => 'Master Record not deleted!',
    'MasterRecordUpdated' => 'Master Record successfully updated!',
    'MasterRecordDeleted' => 'Master Record successfully deleted!',
    'MasterRecordNotSaved' => 'Master Record not saved!',
    'MasterRecordNotUpdated' => 'Master Record not updated!',
    
    //End Admin Msgs
    
    
    //Front Msgs
    'TryAgain' => 'Please try again!',
    'SomethingWentWrong' => 'Something went wrong, try again later!',
    
    'PasswordResetLinkSent' => 'Password reset link has been sent to your email address, Please check your mail inbox!',
    'EmailNotExist' => 'Email Address not exist!',
    
    'PasswordResetSuccess' => 'Password has been reset successfully!',
    'PasswordResetFail' => 'Password not resetted!',
    'PasswordResetInvalidLink' => 'Invalid password reset link!',
    
    'PasswordUpdated' => 'Password has been updated successfully!',
    'IncorrectInfo' => 'Please fill correct information!',
    
    'SettingUpdated' => 'Setting successfully updated!',
    'SettingNotSaved' => 'Setting not saved!', 
    
    'MessageSent' => 'Thank You! Your message has been sent successfully!',
    'MessageNotSent' => 'Your message was not sent!',
    
    'ContactUsMessageSuccess' => 'Thank you for your message! We will get back to you soon!',
    'ContactUsCallbackSuccess' => 'Thank you for contacting us! We will call back to you soon!',
    
    'AccountCreated' => 'Your account has been successfully created!',
    
    'SelectAtleastOneCategory' => 'Please select atleast one category!',
    
    'RatingSubmitted' => 'Your rating submitted successfully!',
    'RatingNotSubmitted' => 'Your rating not submitted!',
    
    'ThankSubmitted' => 'Your thank submitted successfully!',
    'ThankNotSubmitted' => 'Your thank not submitted!',
    
    'QuestionSent' => 'Thank You! Your question has been sent successfully!',
    'QuestionNotSent' => 'Your question was not sent!',
    
    'FeedbackSubmitted' => 'Your feedback submitted successfully!',
    'FeedbackNotSubmitted' => 'Your feedback not submitted!',
    
    'CartUpdated' => 'Cart updated successfully!',
    'CartEmpty' => 'Your shopping cart is empty!',
    'CartOutStock' => 'Out of Stock!',
    'CartMsg' => 'first remove out of stock item then proceed to checkout',
    'CartMsgPmt' => 'Please remove out of stock items from ',
    
    'OrderSaved' => 'Order successfully saved!',
    'OrderNotSaved' => 'Order not saved!',
    'OrderCancelled' => 'Your order has been cancelled successfully!',
    'OrderUpdated' => 'order updated successfully!',
    'OrderNotUpdated' => 'order not updated!',
    
    'ProfileUpdated' => 'Profile successfully updated!',
    'ProfileNotUpdated' => 'Profile not updated!',
    
    'PaymentFailed' => 'Sorry, there was an error processing your payment. Please try again later!',
    'PaymentSuccess' => 'Thank you! Your payment was processed successfully!',
    
    'RefundFailed' => 'Sorry, there was an error processing your refund. Please try again later!',
    'RefundSuccess' => 'Thank you! Your refund was processed successfully!',

    'SiteSettingAdded' => 'Site Setting successfully added!',
    'SiteSettingNotSaved' => 'Site Setting not saved!',
    'SiteSettingDeleted' => 'Site Setting successfully deleted!',
    'SiteSettingNotDeleted' => 'Site Setting not deleted!',
    'SiteSettingUpdated' => 'Site Setting updated successfully!',
    'SiteSettingNotUpdated' => 'Site Setting not updated!',
    
    'AdminUserAdded' => 'Admin User successfully added!',
    'AdminUserNotAdded' => 'Admin User not added!',
    'AdminUserDeleted' => 'Admin User successfully deleted!',
    'AdminUserNotDeleted' => 'This Admin User cannot be deleted!',
    'AdminUserUpdated' => 'Admin User updated successfully!',
    
    
    //End Front Msgs
];
