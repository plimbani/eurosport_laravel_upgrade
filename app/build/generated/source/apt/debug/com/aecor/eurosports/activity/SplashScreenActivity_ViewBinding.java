// Generated code from Butter Knife. Do not modify!
package com.aecor.eurosports.activity;

import android.support.annotation.CallSuper;
import android.support.annotation.UiThread;
import android.view.View;
import butterknife.Unbinder;
import butterknife.internal.DebouncingOnClickListener;
import butterknife.internal.Utils;
import com.aecor.eurosports.R;
import java.lang.IllegalStateException;
import java.lang.Override;

public class SplashScreenActivity_ViewBinding implements Unbinder {
  private SplashScreenActivity target;

  private View view2131427433;

  @UiThread
  public SplashScreenActivity_ViewBinding(SplashScreenActivity target) {
    this(target, target.getWindow().getDecorView());
  }

  @UiThread
  public SplashScreenActivity_ViewBinding(final SplashScreenActivity target, View source) {
    this.target = target;

    View view;
    view = Utils.findRequiredView(source, R.id.cm, "method 'submit'");
    view2131427433 = view;
    view.setOnClickListener(new DebouncingOnClickListener() {
      @Override
      public void doClick(View p0) {
        target.submit(p0);
      }
    });
  }

  @Override
  @CallSuper
  public void unbind() {
    if (target == null) throw new IllegalStateException("Bindings already cleared.");
    target = null;


    view2131427433.setOnClickListener(null);
    view2131427433 = null;
  }
}
